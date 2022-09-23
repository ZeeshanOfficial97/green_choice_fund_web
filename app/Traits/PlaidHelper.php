<?php

namespace App\Traits;

use TomorrowIdeas\Plaid\Plaid;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;

trait PlaidHelper
{
    /**
     * @return object
     */

    public function getPlaidClient(): Plaid
    {


        $plaid = new Plaid(
            '62f178faf8e7e40013eb8c29',
            '1c130690833fb5834f96b5570edb2c',
            'sandbox'
        );

        return $plaid;
    }

    private $AUTHORIZE_CREATE_TRANSFER = '/transfer/authorization/create';
    private $client = null;
    private $clientId = null;
    private $secret = null;
    private $environment = null;

    private function initializePlaidExtension()
    {

        $this->clientId = '62f178faf8e7e40013eb8c29';
        $this->secret = '1c130690833fb5834f96b5570edb2c';
        $this->environment = 'sandbox';
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
     * Authorize Create Transfer
     *
     * @param  array $params
     * @return json
     */
    public function authorizeCreateTransfer($params)
    {
        $this->initializePlaidExtension();
        return $this->makeHttpRequest($this->AUTHORIZE_CREATE_TRANSFER, $params);
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
