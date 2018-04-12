<?php

namespace Tests\Feature;

use App\Job;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewJobsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_see_a_list_of_latest_jobs()
    {
        $jobA = factory(Job::class)->create();
        $jobB = factory(Job::class)->create();
        $jobC = factory(Job::class)->create();

        $response = $this->get('/');
        $response->assertStatus(200);
        
        $this->assertTrue($response->data('jobs')->contains($jobA));
        $this->assertTrue($response->data('jobs')->contains($jobB));
        $this->assertTrue($response->data('jobs')->contains($jobC));
    }

}
