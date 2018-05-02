<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FeaturedJobTest extends TestCase
{

    /** @test */
    public function a_user_can_purchase_a_featured_job()
    {
        // Mock Gateway
        // Mock feature job input data
        // Create mock token with \Stripe\Token::create

        // Hit endpoint

        // Assert Charge on Stripe???? Or create mock and unit test the gateway
        // Assert Featured Job in DB




        // Controller:
        // 1. Validate Request
        // 2. Charge Credict Card (Catch exception?)
        // 3. Create job in DB

        // try {
        //     $gateway->charge($request->token);
        // } catch (Exception $e) {
        //     return response(404)->message('Something went wrong with the payment')
        // }
    }

    /** @test */
    public function if_a_charge_fails_the_user_cannot_purchase_a_featured_job()
    {
        
    }

}
