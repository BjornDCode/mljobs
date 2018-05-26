<?php

namespace Tests\Feature;

use App\Job;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ManageJobsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_administrator_can_access_the_job_dashboard()
    {
        $admin = $this->createAdminUser();

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
    public function an_administrator_can_update_unpublished_an_job()
    {
        $admin = $this->createAdminUser();
        $job = factory(Job::class)->states('unpublished')->create(); 

        $response = $this->actingAs($admin)->patch("/job/{$job->id}", [
            'title' => 'new title',
            'description' => 'updated description',
        ]);

        $job = $job->fresh();

        $this->assertEquals(1, $job->published);
        $this->assertEquals('new title', $job->title);
        $this->assertEquals('updated description', $job->description);

        $response->assertRedirect('/dashboard');
    }

    /** @test */
    public function a_visitor_cannot_update_unpublished_an_job()
    {
        $job = factory(Job::class)->states('unpublished')->create();

        $response = $this->patch("/job/{$job->id}", [
            'title' => 'new title',
            'description' => 'updated description',
        ]);

        $this->assertDatabaseHas('jobs', [
            'title' => $job->title, 
            'description' => $job->description,
            'published' => 0
        ]);

        $response->assertRedirect('/');
    }

    /** @test */
    public function an_administrator_can_delete_an_unpublished_job()
    {
        $admin = $this->createAdminUser();
        $job = factory(Job::class)->states('unpublished')->create(); 

        $response = $this->actingAs($admin)->delete("/job/{$job->id}");

        $this->assertDatabaseMissing('jobs', [
            'title' => $job->title
        ]);
        $response->assertRedirect('/dashboard');
    }

    /** @test */
    public function a_visitor_cannot_delete_an_unpublished_job()
    {
        $job = factory(Job::class)->states('unpublished')->create(); 

        $response = $this->delete("/job/{$job->id}");

        $this->assertDatabasehas('jobs', [
            'title' => $job->title
        ]);
        $response->assertRedirect('/');
    }

}
