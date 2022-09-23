<?php

namespace App\Http\Controllers\Api\V1;

use App\Helpers\Constant;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;

use App\Traits\PlaidHelper;
use TomorrowIdeas\Plaid\Entities\User;

class PlaidController extends ApiController
{

    use PlaidHelper;

    public function createLinkToken(Request $request)
    {
        try {
            $data = $this->getPlaidClient()->tokens->create(
                "client_name",
                "en",
                ["US"],
                new User('user 1234'),
                ["transfer"],
                android_package_name: 'com.greenchoicefund'
            );
            return $this->successResponse("Link token", $data, 'Link token created');
        } catch (\Throwable $th) {
            return $this->exceptionResponse($th);
        }
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
        $amount = $request->get('amount');

        $options = ['account_ids' => array($account_id)];
        $accountDetails = $this->getPlaidClient()->accounts->list($access_token, $options);

        if (!(isset($accountDetails) && isset($accountDetails->accounts[0]))) {
        }
        if ($accountDetails->accounts[0]->balances->available < $amount) {
        }

        $transferReqObj = [
            'access_token' => $access_token,
            'account_id' => $account_id,
            'amount' => $amount,
            'type' => 'credit',
            'network' => 'ach',
            'ach_class' => 'ppd',
            'description' => null,
            'metadata' => null,
            'user' => [
                'legal_name' => 'Test user',
                'email_address' => 'test@gmail.com',
                'address' => [
                    'street' => '123 Main St.',
                    'city' => 'San Francisco',
                    'region' => 'CA',
                    'postal_code' => '94053',
                    'country' => 'US',
                ],
            ]
        ];

        $transferAuthorizationResponse = $this->authorizeCreateTransfer($transferReqObj);
        return $transferAuthorizationResponse;
        return $this->successResponse("Account details", $accountDetails, 'Account details');
    }

}
