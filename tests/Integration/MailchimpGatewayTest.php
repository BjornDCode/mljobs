<?php

namespace Tests\Integration;

use Tests\TestCase;
use App\MailchimpGateway;
use Newsletter;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MailchimpGatewayTest extends TestCase
{

    /** @test */
    public function it_can_subscribe_a_user()
    {
        $mailchimp = new MailchimpGateway;

        $mailchimp->subscribe('asbjorn.lindholm@gmail.com');

        $this->assertTrue(Newsletter::hasMember('asbjorn.lindholm@gmail.com'));
    }

    /** @test */
    public function it_rejects_an_with_invalid_details()
    {
        $mailchimp = new MailchimpGateway;

        $response = $mailchimp->subscribe('asbjorn.lindholm');

        $this->assertFalse($response);
    }

}
