<?php

namespace App\Http\Controllers\Api\V1;

use App\Helpers\Constant;
use App\Http\Controllers\ApiController;
use App\Models\UserInvestment;
use App\Models\UserInvestmentSolution;
use Illuminate\Http\Request;

use App\Traits\PlaidClient;
use App\Traits\StripeClient;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use TomorrowIdeas\Plaid\Entities\User;
use TomorrowIdeas\Plaid\Resources\Investments;

class PlaidController extends ApiController
{

    use PlaidClient, StripeClient;

    public function createLinkToken(Request $request)
    {
        // try {
        $data = $this->getPlaidClient()->tokens->create(
            "Green Choice Fund LLC",
            "en",
            ["US"],
            new User('user 1234'),
            ["auth"],
            android_package_name: 'com.greenchoicefund'
        );
        return $this->successResponse("Link token", $data, 'Link token created');
        // } catch (\Throwable $th) {
        //     return $this->exceptionResponse($th);
        // }
    }

    public function setAccessToken(Request $request)
    {
        $data = $this->getPlaidClient()->items->exchangeToken($request->get('public_token'));
        return $this->successResponse("Public token exchanged with access token successfully", $data, 'Access Token');
    }

    public function getAccountsDetails(Request $request)
    {
        $options = [];
        if ($request->get('account_ids')) {
            $options['account_ids'] = $request->get('account_ids');
        }
        $data = $this->getPlaidClient()->accounts->list($request->get('access_token'), $options);
        return $this->successResponse("Account details", $data, 'Account details');
    }

    public function transferFunds(Request $request)
    {
        $account_id = $request->get('account_id');
        $access_token = $request->get('access_token');
        $name = $request->get('name');
        $email = $request->get('email');
        $dob = $request->get('dob');
        $billing_address = $request->get('billing_address');
        $amount = $request->get('amount');
        $solutions = $request->get('solutions');
        $user = Auth::user();

        $options = ['account_ids' => array($account_id)];
        $accountDetails = $this->getPlaidClient()->accounts->list($access_token, $options);

        if (!(isset($accountDetails) && isset($accountDetails->accounts[0]))) {
        }
        if ($accountDetails->accounts[0]->balances->available < $amount) {
        }

        $stripeBankAccountToken = $this->createStripeBankAccountToken($access_token, $accountDetails->accounts[0]->account_id);

        $investmentData = [
            'investment_num' => time(),
            'name' => $name,
            'email' => $email,
            'dob'  => $dob,
            'billing_address' => $billing_address,
            'invested_amount' => $amount,
            'user_id' => $user->id
        ];

        DB::beginTransaction();

        $investment = UserInvestment::create($investmentData);

        if (isset($solutions)) {
            foreach ($solutions as $id) {
                $investmentSolutions[] = array('investment_id' => $investment->id, 'solution_id' => $id, 'user_id' => $user->id);
            }

            if (isset($investmentSolutions)) {
                UserInvestmentSolution::insert($investmentSolutions);
            }
        }

        $stripe = $this->getStripeClient();
        $stripeCharge = $stripe->charges->create([
            'amount' => 2000 * 100,
            'currency' => 'usd',
            'source' => $stripeBankAccountToken,
            'customer' => Auth::user()->stripe_user_id,
            'receipt_email' => Auth::user()->email,
            'description' => 'Bank transfer for investment. Investment #' . $investment->investment_num . ' for user ' . $user->email,
            'statement_descriptor_suffix' => 'Green Choice Fund LLC',
            'metadata' => [
                            'investment_num' => $investment->investment_num,
                            'invested_amount' => $investment->invested_amount,
                            'name' => $investment->name,
                            'email' => $investment->email,
                            'dob'  => $investment->dob,
                            'billing_address' => $investment->billing_address,
                            'green_choice_fund_user' => $user->email
                        ]
        ]);

        if (isset($stripeCharge)) {
            if (!($stripeCharge->id != null && $stripeCharge->paid && $stripeCharge->status == 'succeeded')) {

            }
        }
        $investment->update(['stripe_charge_id' => $stripeCharge->id]);

        DB::commit();

        return $this->successResponse("Account details", $accountDetails, 'Account details');
    }
}
