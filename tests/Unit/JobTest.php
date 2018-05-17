<?php

namespace Tests\Unit;

use App\Job;
use App\Customer;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class JobTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_belongs_to_a_customer()
    {
        $job = factory(Job::class)->create();
        $this->assertInstanceOf(Customer::class, $job->customer);
    }

}
