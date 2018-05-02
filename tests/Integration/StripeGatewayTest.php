<?php

namespace Tests\Integration;

use Tests\TestCase;
use App\StripeGateway;
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

        try {
            $this->assertEquals($charge->id, \Stripe\Charge::retrieve($charge->id)->id);
        } catch (Exception $e) {
            $this->fail('Expected to see a Stripe charge, but did not');            
        }
    }

    /** @test */
    public function it_rejects_an_invalid_credit_card()
    {
        $stripeGateway = new StripeGateway;

        // $token = invalidToken

        $charge = $stripeGateway->charge($invalidToken);

        // Assert exception was thrown?
    }

}
