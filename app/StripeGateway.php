<?php 

namespace App;

use Stripe\Charge;
use Stripe\Stripe;
use App\Exceptions\PaymentFailedException;

class StripeGateway 
{

    public function __construct() 
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function charge($token) 
    {
        try {
            $charge = Charge::create([
                'amount' => 4900,
                'currency' => 'usd',
                'source' => $token
            ]);
        } catch (\Stripe\Error\Card | Exception $e) {
            throw new PaymentFailedException;
        }

        return $charge;
    }

}
