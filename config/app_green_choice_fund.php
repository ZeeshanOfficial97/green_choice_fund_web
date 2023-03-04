<?php

use Illuminate\Support\Facades\Facade;

return [

    'privacy_policy_version' => env('PRIVACY_POLICY_VERSION'),

    'page_size_sm' => env('PAGE_SIZE_SM', 10),
    'page_size_md' => env('PAGE_SIZE_MD', 15),
    'page_size_lg' => env('PAGE_SIZE_LG', 20),
    'page_size_xl' => env('PAGE_SIZE_XL', 25),


    'firebase_key' => env('FIREBASE_SERVER_KEY'),

    'stripe_public_key' => env('STRIPE_PUBLIC_KEY'),
    'stripe_secret' => env('STRIPE_SECRET'),
    'stripe_payment_url' => env('STRIPE_PAYMENT_URL'),

    'paypal_payment_url' => env('PAYPAL_PAYMENT_URL'),


    'plaid_client_id' => env('PLAID_CLIENT_ID'),
    'plaid_secret' => env('PLAID_SECRET'),
    'plaid_env' => env('PLAID_ENV'),
    'plaid_products' => env('PLAID_PRODUCTS'),
    'plaid_country_codes' => env('PLAID_COUNTRY_CODES'),

];
