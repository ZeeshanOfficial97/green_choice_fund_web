<?php

namespace App\Http\Controllers\Api\V1;

use App\Helpers\Constant;
use App\Http\Controllers\ApiController;
use App\Http\Requests\UserInvestments\V1\SaveUserInvestmentRequest;
use App\Http\Resources\Category\Api\V1\InvestmentResource;
use App\Http\Resources\Category\Api\V1\InvestmentResourceCollection;
use App\Models\StripeChargeAndRefund;
use App\Models\UserInvestment;
use App\Models\UserInvestmentSolution;
use App\Services\Api\V1\InvestmentService;
use Illuminate\Http\Request;

use App\Traits\PlaidClient;
use App\Traits\StripeClient;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use TomorrowIdeas\Plaid\Entities\User;
use TomorrowIdeas\Plaid\Resources\Investments;

class InvestmentController extends ApiController
{

    use PlaidClient, StripeClient;

    /**
     * @var InvestmentService
     */
    private $investmentService;

    /**
     * @param InvestmentService $investmentService
     */
    public function __construct(InvestmentService $investmentService)
    {
        $this->investmentService = $investmentService;
    }

    public function saveUserInvestment(SaveUserInvestmentRequest $request)
    {

        try {
            $account_id = $request->get('account_id');
            $access_token = $request->get('access_token');
            $investment_amount = $request->get('investment_amount');
            $user = Auth::user();

            $options = ['account_ids' => array($account_id)];
            $accountDetails = $this->getPlaidClient()->accounts->list($access_token, $options);

            if (!(isset($accountDetails) && isset($accountDetails->accounts[0]))) {
                return $this->errorResponse("Invalid account", ['account_id' => 'The account id is invalid'], 610);
            }
            if ($accountDetails->accounts[0]->balances->available < $investment_amount) {
                return $this->errorResponse("Insufficient balance", ['investment_amount' => 'This account has insufficient funds'], 610);
            }

            $stripeBankAccountToken = $this->createStripeBankAccountToken($access_token, $accountDetails->accounts[0]->account_id);

            DB::beginTransaction();
            $investment = $this->investmentService->saveUserInvestment($request);

            \Stripe\Stripe::setApiKey(config('app_green_choice_fund.stripe_secret'));
            \Stripe\Customer::createSource(
                $user->stripe_user_id,
                ['source' => $stripeBankAccountToken]
            );

            $stripe = $this->getStripeClient();
            $stripeCharge = $stripe->charges->create([
                'amount' => $investment_amount * 100,
                'currency' => 'usd',
                'source' => $stripeBankAccountToken,
                'customer' => $user->stripe_user_id,
                'receipt_email' => $user->email,
                'description' => 'Bank transfer for investment. Investment #' . $investment->investment_num . ' for user ' . $user->email,
                'statement_descriptor_suffix' => 'Green Choice Fund LLC',
                'metadata' => [
                    'investment_num' => $investment->investment_num,
                    'invested_amount' => $investment->invested_amount,
                    'name' => $investment->name,
                    'email' => $investment->email,
                    'country_code' => $investment->country_code,
                    'country_no' => $investment->contact_no,
                    'dob'  => $investment->dob,
                    'address' => $investment->address,
                    'green_choice_fund_user' => $user->email
                ]
            ]);

            if (isset($stripeCharge)) {
                if (!($stripeCharge->id != null && $stripeCharge->paid && $stripeCharge->status == 'succeeded')) {
                    return $this->errorResponse("An error occured while transferring the funds", ['investment' => 'An error occured while transferring the funds'], 610);
                }
            }

            $this->investmentService->updateStripeChargeId($investment, $stripeCharge->id);

            $investment = $this->investmentService->investmentInstanceSave($investment);

            DB::commit();

            return $this->successResponse("Investment funds has been transferred successfully", new InvestmentResource($investment), 'Investment saved');
        } catch (\Throwable $th) {
            DB::rollBack();
            if (isset($stripeCharge)) {
                if (($stripeCharge->id != null && $stripeCharge->paid && $stripeCharge->status == 'succeeded')) {
                    $stripe = $this->getStripeClient();
                    $stripeRefund = $stripe->refunds->create([
                        'charge' => $stripeCharge->id
                    ]);
                    $this->investmentService->saveStripeRefund($stripeCharge, $stripeRefund, $user);
                }
            }


            return $this->exceptionResponse($th);
        }
    }

    public function getUserInvestments(Request $request)
    {
        try {
            $data = $this->investmentService->getUserInvestmentsList($request, true);
            $result = new InvestmentResourceCollection($data);
            return $this->successResponse("User investments list", $result);
        } catch (\Throwable $th) {
            return $this->exceptionResponse($th);
        }
    }

    public function getUserInvestment(Request $request)
    {
        try {
            if ($data = $this->investmentService->getUserInvestment($request, true)) {
                $result = new InvestmentResource($data);
                return $this->successResponse("User investments list", $result);
            } else {
                return $this->errorResponse("Record not found", [
                    'investment' => 'Record not found',
                ], 404, statusCode: 404);
            }
        } catch (\Throwable $th) {
            return $this->exceptionResponse($th);
        }
    }
}
