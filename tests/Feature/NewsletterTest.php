<?php

namespace Tests\Feature;

use \Mockery;
use Tests\TestCase;
use App\MailchimpGateway;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NewsletterTest extends TestCase
{

    public function tearDown() 
    {
        parent::tearDown();

        Mockery::close();
    }
    
    /** @test */
    public function a_user_can_subscribe_to_the_newsletter()
    {
        $gateway = Mockery::spy(MailchimpGateway::class);
        
        $this->app->instance(MailchimpGateway::class, $gateway);

        $response = $this->json('POST', '/newsletter', [
            'email' => 'test@example.com'
        ]);

        $gateway->shouldHaveReceived('subscribe')->atLeast()->once();
        $response->assertStatus(200);
    }

    /** @test */
    public function a_user_cannot_subscribe_with_an_invalid_email()
    {
        $this->withExceptionHandling();
        
        $mailchimp = Mockery::mock(MailchimpGateway::class);

        $response = $this->json('POST', '/newsletter', [
            'email' => 'not-an-email'
        ]);

        $response->assertStatus(422);
    }

}
