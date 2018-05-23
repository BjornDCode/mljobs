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

    /** @test */
    public function a_visitor_cannot_see_a_single_unpublished_job()
    {
        $job = factory(Job::class)->states('unpublished')->create();

        $response = $this->get("/unpublished/{$job->id}");
        
        $response->assertRedirect('/');
    }

    /** @test */
    public function an_administrator_can_update_unpublished_an_job()
    {
        $admin = $this->createAdmin();
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

    private function createAdmin() {
        $admin = factory(User::class)->create();
        config([ 'app.administrators' => [ $admin->email ] ]);
        return $admin;
    }

}
