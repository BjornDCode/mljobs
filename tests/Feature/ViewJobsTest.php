<?php

namespace Tests\Feature;

use App\Job;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewJobsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_see_a_list_of_latest_jobs()
    {
        $jobA = factory(Job::class)->states('full')->create();
        $jobB = factory(Job::class)->states('full')->create();
        $jobC = factory(Job::class)->states('full')->create();

        $response = $this->get('/');
        $response->assertStatus(200);

        $this->assertTrue($response->data('jobs')->contains($jobA));
        $this->assertTrue($response->data('jobs')->contains($jobB));
        $this->assertTrue($response->data('jobs')->contains($jobC));

    }

    /** @test */
    public function jobs_are_displayed_in_descending_order() 
    {
        $jobA = factory(Job::class)->states('full')->create(['created_at' => Carbon::parse('-2 weeks')]);
        $jobB = factory(Job::class)->states('full')->create(['created_at' => Carbon::parse('-3 weeks')]);
        $jobC = factory(Job::class)->states('full')->create(['created_at' => Carbon::parse('-1 week')]);
        
        $response = $this->get('/');

        $this->assertEquals([
            $jobC->id,
            $jobA->id,
            $jobB->id,
        ], $response->data('jobs')->pluck('id')->values()->all());
    }

    /** @test */
    public function a_user_can_view_a_single_job()
    {
        $job = factory(Job::class)->states('full')->create();

        $response = $this->get("/job/{$job->id}");
        
        $response->assertStatus(200);
        $response->assertViewHas('job', $job->fresh());
    }

    /** @test */
    public function a_user_cant_view_a_job_that_does_not_exist()
    {
        $response = $this->withExceptionHandling()->get("/job/1");

        $response->assertStatus(404);
    }

}
