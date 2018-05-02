<?php

namespace Tests\Feature;

use \Mockery;
use Tests\TestCase;
use App\StripeGateway;
use App\Exceptions\PaymentFailedException;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FeaturedJobTest extends TestCase
{
    use RefreshDatabase;

    public function tearDown() 
    {
        parent::tearDown();

        Mockery::close();
    }

    /** @test */
    public function a_user_can_purchase_a_featured_job()
    {
        $gateway = Mockery::spy(StripeGateway::class);
        $this->app->instance(StripeGateway::class, $gateway);

        $data = [
            'token' => 'a-valid-stripe-token',
            'job' => [
                'title' => 'A job title',
                'description' => 'This is the job description',
                'apply_url' => 'http://example.com',
                'company' => null,
                'company_logo' => null,
                'location' => null,
                'salary' => null,
                'type' => null,
                'featured' => 1,
            ]
        ];

        $this->postJson('/featured-job/store', $data);

        $gateway->shouldHaveReceived('charge')->once();
        $this->assertDatabaseHas('jobs', [
            'title' => $data['job']['title'],
            'featured' => 1
        ]);
    }

    /** @test */
    public function the_user_cannot_purchase_a_featured_job_if_the_charge_fails()
    {
        $gateway = Mockery::mock(StripeGateway::class);
        $this->app->instance(StripeGateway::class, $gateway);
        $gateway->shouldReceive('charge')->andThrow(new PaymentFailedException);

        $data = [
            'token' => 'an-invalid-stripe-token',
            'job' => [
                'title' => 'A job title',
                'description' => 'This is invalid job data',
                'apply_url' => 'http://example.com',
                'featured' => 1
            ]
        ];

        $response = $this->postJson('/featured-job/store', $data);

        $response->assertStatus(422);
        $this->assertDatabaseMissing('jobs', [
            'title' => $data['job']['title'],
        ]);
    }

    /** @test */
    public function the_user_cannot_purchase_a_featured_job_if_they_provide_invalid_job_data()
    {
        $data = [
            'token' => 'a-valid-stripe-token',
            'job' => [
                'title' => 'A job title',
                'description' => 'This is invalid job data',
            ]
        ];

        $response = $this->postJson('/featured-job/store', $data);

        $response->assertStatus(422);
        $this->assertDatabaseMissing('jobs', [
            'title' => $data['job']['title'],
        ]);
    }

}
