<?php 

namespace App;

class StripeGateway 
{

    public function __construct() 
    {
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function charge($token) 
    {
        return \Stripe\Charge::create([
            'amount' => 4900,
            'currency' => 'usd',
            'source' => $token
        ]);
    }

}
