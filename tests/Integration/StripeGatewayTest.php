<?php

namespace Tests\Integration;

use Tests\TestCase;
use App\StripeGateway;
use App\Exceptions\PaymentFailedException;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StripeGatewayTest extends TestCase
{
    
    /** @test */
    public function it_can_create_a_charge_on_stripe()
    {
        $stripeGateway = new StripeGateway;

        $validToken = \Stripe\Token::create([
            'card' => [
                'number' => '4242424242424242',
                'exp_month' => 1,
                'exp_year' => 2020,
                'cvc' => 123
            ]
        ]);

        $charge = $stripeGateway->charge($validToken);

        $this->assertEquals($charge->id, \Stripe\Charge::retrieve($charge->id)->id);
    }

    /** @test */
    public function it_rejects_an_invalid_credit_card()
    {
        $stripeGateway = new StripeGateway;

        $invalidToken = \Stripe\Token::create([
            'card' => [
                'number' => '4000000000000002',
                'exp_month' => 1,
                'exp_year' => 2020,
                'cvc' => 123
            ]
        ]);

        $this->expectException(PaymentFailedException::class);

        $charge = $stripeGateway->charge($invalidToken);

    }

}
