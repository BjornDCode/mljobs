<?php

namespace Tests\Feature;

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

}
