<?php

namespace Tests\Feature;

use App\Job;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ManageJobsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_administrator_can_access_the_job_dashboard()
    {
        $admin = factory(User::class)->create();
        config([ 'app.administrators' => [ $admin->email ] ]);
        $admin = $this->createAdmin();

        $this->actingAs($admin)
            ->get('/dashboard')
            ->assertStatus(200)
            ->assertSee('Dashboard');
    }

    /** @test */
    public function a_visitor_cannot_access_the_job_dashboard()
    {
        $this->get('/dashboard')
            ->assertRedirect('/');
    }

    /** @test */
    public function an_administrator_can_see_a_list_of_unpublished_jobs()
    {
        $admin = $this->createAdmin();

        $publishedJob = factory(Job::class)->states('full')->create();
        $unpublishedJob = factory(Job::class)->states('unpublished')->create();
        $anotherUnpublishedJob = factory(Job::class)->states('unpublished')->create();

        $response = $this->actingAs($admin)->get('/dashboard');

        $this->assertFalse($response->data('jobs')->contains($publishedJob));
        $this->assertTrue($response->data('jobs')->contains($unpublishedJob));
        $this->assertTrue($response->data('jobs')->contains($anotherUnpublishedJob));
    }

    /** @test */
    public function an_administrator_can_see_a_single_unpublished_job()
    {
        $admin = $this->createAdmin();
        $job = factory(Job::class)->states('unpublished')->create();

        $response = $this->actingAs($admin)->get("/unpublished/{$job->id}");
        
        $response->assertStatus(200);
        $response->assertViewHas('job', $job->fresh());
    }
}
