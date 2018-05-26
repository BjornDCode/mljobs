<?php

namespace Tests\Feature;

use App\User;
use \Mockery;
use Tests\TestCase;
use App\StripeGateway;
use App\Mail\FeaturedJobPurchased;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Exceptions\PaymentFailedException;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateJobTest extends TestCase
{
    use RefreshDatabase;

    public function tearDown() 
    {
        parent::tearDown();

        Mockery::close();
    }

    /** @test */
    public function a_user_can_see_the_purchase_job_page()
    {
        $response = $this->get('/job/create');
        $response->assertStatus(200);
        $response->assertSee('Post a job');
    }

    /** @test */
    public function an_admin_can_create_a_job()
    {
        $admin = factory(User::class)->create();
        config([ 'app.administrators' => [ $admin->email ] ]);

        $data = [
            'title' => 'A job title',
            'description' => 'This is the job description',
            'apply_url' => 'http://example.com',
            'company' => null,
            'location' => null,
            'salary' => null,
            'type' => null,
        ];

        $response = $this->actingAs($admin)->post('/job/store', $data);

        $response->assertRedirect('/');
        $this->assertDatabaseHas('jobs', [
            'title' => $data['title'],
            'published' => 1
        ]);
    }

    /** @test */
    public function a_user_can_purchase_a_job_listing()
    {
        $gateway = Mockery::spy(StripeGateway::class);
        $this->app->instance(StripeGateway::class, $gateway);

        $data = [
            'token' => 'a-valid-stripe-token',
            'email' => 'test@example.com',
            'job' => [
                'title' => 'A job title',
                'description' => 'This is the job description',
                'apply_url' => 'http://example.com',
                'company' => null,
                'location' => null,
                'salary' => null,
                'type' => null,
            ]
        ];

        $response = $this->postJson('/job/store', $data);

        $response->assertStatus(201);
        $gateway->shouldHaveReceived('charge')->once();
        $this->assertDatabaseHas('jobs', [
            'title' => $data['job']['title'],
            'published' => 1
        ]);
    }

    
    /** @test */
    public function a_user_can_purchase_a_job_listing_with_a_company_logo()
    {
        $gateway = Mockery::spy(StripeGateway::class);
        $this->app->instance(StripeGateway::class, $gateway);

        $data = [
            'token' => 'a-valid-stripe-token',
            'email' => 'test@example.com',
            'job' => [
                'title' => 'A job title',
                'description' => 'This is the job description',
                'apply_url' => 'http://example.com',
                'company' => null,
                'company_logo' => '/images/logo.png',
                'location' => null,
                'salary' => null,
                'type' => null,
            ]
        ];

        $response = $this->postJson('/job/store', $data);

        $this->assertDatabaseHas('jobs', [
            'title' => $data['job']['title'],
            'company_logo' => $data['job']['company_logo'],
        ]);
    }

    /** @test */
    public function the_user_cannot_purchase_a_job_listing_if_the_charge_fails()
    {
        $gateway = Mockery::mock(StripeGateway::class);
        $this->app->instance(StripeGateway::class, $gateway);
        $gateway->shouldReceive('charge')->andThrow(new PaymentFailedException);

        $data = [
            'token' => 'an-invalid-stripe-token',
            'email' => 'test@example.com',
            'job' => [
                'title' => 'A job title',
                'description' => 'This is invalid job data',
                'apply_url' => 'http://example.com'
            ]
        ];

        $response = $this->postJson('/job/store', $data);

        $response->assertStatus(422);
        $this->assertDatabaseMissing('jobs', [
            'title' => $data['job']['title'],
        ]);
    }

    /** @test */
    public function the_user_cannot_purchase_a_job_listing_if_they_provide_invalid_billing_data()
    {
        $this->withExceptionHandling();

        $data = [
            'token' => 'a-valid-stripe-token',
            'email' => 'not-an-email',
            'job' => [
                'title' => 'A job title',
                'description' => 'This is invalid job data',
                'apply_url' => 'http://example.com'
            ]
        ];

        $response = $this->postJson('/job/store', $data);

        $response->assertStatus(422);
        $this->assertDatabaseMissing('jobs', [
            'title' => $data['job']['title'],
        ]);
    }

    /** @test */
    public function the_user_cannot_purchase_a_job_listing_if_they_provide_invalid_job_data()
    {
        $this->withExceptionHandling();

        $data = [
            'token' => 'a-valid-stripe-token',
            'email' => 'test@example.com',
            'job' => [
                'title' => 'A job title',
                'description' => 'This is invalid job data',
            ]
        ];

        $response = $this->postJson('/job/store', $data);

        $response->assertStatus(422);
        $this->assertDatabaseMissing('jobs', [
            'title' => $data['job']['title'],
        ]);
    }

    /** @test */
    public function a_user_is_emailed_a_receipt_when_they_purchase_a_job()
    {
        Mail::fake();
        $gateway = Mockery::spy(StripeGateway::class);
        $this->app->instance(StripeGateway::class, $gateway);

        $data = [
            'token' => 'a-valid-stripe-token',
            'email' => 'test@example.com',
            'job' => [
                'title' => 'A job title',
                'description' => 'This is the job description',
                'apply_url' => 'http://example.com',
            ]
        ];

        $job = $this->postJson('/job/store', $data);

        $this->assertDatabaseHas('customers', [
            'email' => $data['email']
        ]);
        Mail::assertSent(FeaturedJobPurchased::class, function($mail) use($job, $data) {
            return $mail->hasTo($data['email']) &&
                   $mail->job->id === $job->original->id;
        });
    }

    /** @test */
    public function the_job_description_can_be_written_in_markdown_and_parsed_to_html()
    {
        $gateway = Mockery::spy(StripeGateway::class);
        $this->app->instance(StripeGateway::class, $gateway);

        $data = [
            'token' => 'a-valid-stripe-token',
            'email' => 'test@example.com',
            'job' => [
                'title' => 'A job title',
                'description' => 'This is the job description',
                'apply_url' => 'http://example.com',
            ]
        ];

        $response = $this->postJson('/job/store', $data);

        $this->assertDatabaseHas('jobs', [
            'title' => $data['job']['title'],
            'description' => "<p>{$data['job']['description']}</p>",
        ]);
    }

}
