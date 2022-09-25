<?php

namespace App\Traits;

use TomorrowIdeas\Plaid\Plaid;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use Stripe;

trait StripeClient
{
    /**
     * @return object
     */

    public function getStripeClient()
    {
        $stripe = new \Stripe\StripeClient(config('app_green_choice_fund.stripe_secret'));
        return $stripe;
    }
}
