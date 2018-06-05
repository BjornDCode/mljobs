<?php 

namespace App;

use Stripe\Charge as StripeCharge;
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
            $charge = StripeCharge::create([
                'amount' => 4900,
                'currency' => 'usd',
                'source' => $token
            ]);
        } catch (\Stripe\Error\Card | Exception $e) {
            throw new PaymentFailedException;
        }

        return new Charge($charge->id, $charge->amount);
    }

}
