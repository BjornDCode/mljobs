<?php

namespace Tests\Unit;

use App\Job;
use App\Customer;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CustomerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_customer_knows_about_its_featured_jobs()
    {
        $customer = factory(Customer::class)->create();
        $job = factory(Job::class)->create([ 'customer_id' => $customer->id ]);

        $this->assertTrue($customer->jobs->contains($job));
    }

}
