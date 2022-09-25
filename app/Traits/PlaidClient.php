<?php

namespace App\Traits;

use TomorrowIdeas\Plaid\Plaid;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;

trait PlaidClient
{
    /**
     * @return object
     */

    public function getPlaidClient(): Plaid
    {

        $plaid = new Plaid(
            config('app_green_choice_fund.plaid_client_id'),
            config('app_green_choice_fund.plaid_secret'),
            config('app_green_choice_fund.plaid_env'),
        );

        return $plaid;
    }

    /**
     * Get the Stripe bank account token.
     *
     * @param  string  $accessToken
     * @param  string  $accountId
     * @return string  stripe_bank_account_token
     */
    public function createStripeBankAccountToken($accessToken, $accountId)
    {
        $this->initializePlaidExtension();
        $btokParams = [
            'access_token' => $accessToken,
            'account_id' => $accountId,
        ];
        $ACCOUNT_TOKEN_URL = '/processor/stripe/bank_account_token/create';
        return $this->makeHttpRequest($ACCOUNT_TOKEN_URL, $btokParams)->stripe_bank_account_token;
    }

    private $clientId = null;
    private $secret = null;
    private $environment = null;
    private $client = null;

    private function initializePlaidExtension()
    {
        $this->clientId = config('app_green_choice_fund.plaid_client_id');
        $this->secret = config('app_green_choice_fund.plaid_secret');
        $this->environment = config('app_green_choice_fund.plaid_env');
        $this->client = new Client([
            'base_uri' => "https://{$this->environment}.plaid.com",
        ]);
        $this->validateKeys();
    }

    /**
     * Review if any key is null and verify that the environment is one of the
     * accepted by Plaid.
     */
    private function validateKeys()
    {
        if (in_array(null, [$this->secret, $this->clientId, $this->environment])) {
            throw self::missingKeys();
        }

        if (!in_array($this->environment, ['sandbox', 'development', 'production'])) {
            throw self::invalidEnvironment();
        }
    }

    private static function badRequest($message)
    {
        return new static($message);
    }


    private static function missingKeys()
    {
        return new static('{ "display_message": null, "error_code": "MISSING_KEYS", "error_message": "Missing Plaid credentials.", "error_type": "INVALID_INPUT" }');
    }


    private static function invalidEnvironment()
    {
        return new static('{ "display_message": null, "error_code": "INVALID_ENVIRONMENT", "error_message": "The environment must be: sandbox, development or production.", "error_type": "INVALID_INPUT" }');
    }

    private function paramsWithClientCredentials(array $params = []): array
	{
		return \array_merge([
			"client_id" => $this->clientId,
			"secret" => $this->secret
		], $params);
	}


    /**
     * Create a POST request to the provided url with the corresponding
     * parameters.
     *
     * @param  string  $url
     * @param  array  $params
     * @return json
     */
    public function makeHttpRequest($url, $params)
    {
        try {
            $params = $this->paramsWithClientCredentials($params);
            $response = $this->client->request('POST', $url, [
                'headers' => ['Content-Type' => 'application/json'],
                'connect_timeout' => 30,
                'timeout' => 80,
                'body' => json_encode($params),
            ]);
        } catch (ClientException $e) {
            dd($e);
            throw self::badRequest($e->getResponse()->getBody());
        } catch (ServerException $e) {
            throw self::badRequest($e->getResponse()->getBody());
        }

        return json_decode($response->getBody());
    }
}
