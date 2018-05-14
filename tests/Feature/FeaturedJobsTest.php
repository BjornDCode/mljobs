<?php

namespace Tests\Feature;

use \Mockery;
use Tests\TestCase;
use App\StripeGateway;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
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
            'email' => 'test@example.com',
            'logo' => null,
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

        $response = $this->postJson('/featured-job/store', $data);

        $response->assertStatus(200);
        $gateway->shouldHaveReceived('charge')->once();
        $this->assertDatabaseHas('jobs', [
            'title' => $data['job']['title'],
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
            'email' => 'test@example.com',
            'job' => [
                'title' => 'A job title',
                'description' => 'This is invalid job data',
                'apply_url' => 'http://example.com'
            ]
        ];

        $response = $this->postJson('/featured-job/store', $data);

        $response->assertStatus(422);
        $this->assertDatabaseMissing('jobs', [
            'title' => $data['job']['title'],
        ]);
    }

    /** @test */
    public function the_user_cannot_purchase_a_featured_job_if_they_provide_invalid_billing_data()
    {
        $data = [
            'token' => 'a-valid-stripe-token',
            'email' => 'not-an-email',
            'job' => [
                'title' => 'A job title',
                'description' => 'This is invalid job data',
                'apply_url' => 'http://example.com'
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
            'email' => 'test@example.com',
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

    /** @test */
    public function a_user_can_upload_a_company_logo()
    {
        Storage::fake('public');

        $gateway = Mockery::spy(StripeGateway::class);
        $this->app->instance(StripeGateway::class, $gateway);

        $image = UploadedFile::fake()->image('logo.jpg', 100, 100);
        $data = [
            'token' => 'a-valid-stripe-token',
            'email' => 'test@example.com',
            'logo' => $image,
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

        $this->postJson('/featured-job/store', $data);

        Storage::disk('public')->assertExists("images/{$image->hashName()}");
        $this->assertDatabaseHas('jobs', [
            'company_logo' => "images/{$image->hashName()}"
        ]);
    } 

    /** @test */
    public function a_user_cannot_upload_an_image_that_is_too_small()
    {
        Storage::fake('public');

        $gateway = Mockery::spy(StripeGateway::class);
        $this->app->instance(StripeGateway::class, $gateway);

        $image = UploadedFile::fake()->image('logo.jpg', 10, 10);
        $data = [
            'token' => 'a-valid-stripe-token',
            'email' => 'test@example.com',
            'logo' => $image,
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

        $response = $this->postJson('/featured-job/store', $data);

        $response->assertStatus(422);
        Storage::disk('public')->assertMissing("images/{$image->hashName()}");
        $this->assertDatabaseMissing('jobs', [
            'company_logo' => "images/{$image->hashName()}"
        ]);
    }

    /** @test */
    public function a_user_cannot_upload_a_non_image_file()
    {
        Storage::fake('public');

        $gateway = Mockery::spy(StripeGateway::class);
        $this->app->instance(StripeGateway::class, $gateway);

        $pdf = UploadedFile::fake()->create('logo.pdf');
        $data = [
            'token' => 'a-valid-stripe-token',
            'email' => 'test@example.com',
            'logo' => $pdf,
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

        $response = $this->postJson('/featured-job/store', $data);

        $response->assertStatus(422);
    }

}
