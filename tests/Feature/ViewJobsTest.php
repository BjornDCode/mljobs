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

        $this->assertTrue($response->data('groups')['Today']->contains($jobA));
        $this->assertTrue($response->data('groups')['Today']->contains($jobB));
        $this->assertTrue($response->data('groups')['Today']->contains($jobC));
    }

    /** @test */
    public function a_user_can_only_see_published_jobs()
    {
        $publishedJob = factory(Job::class)->states('full')->create();
        $anotherPublishedJob = factory(Job::class)->states('full')->create();
        $unpublishedJob = factory(Job::class)->states('unpublished')->create();

        $response = $this->get('/');

        $this->assertTrue($response->data('groups')['Today']->contains($publishedJob));
        $this->assertTrue($response->data('groups')['Today']->contains($anotherPublishedJob));
        $this->assertFalse($response->data('groups')['Today']->contains($unpublishedJob));
    }

    /** @test */
    public function jobs_are_grouped_by_age()
    {
        $jobFromToday = factory(Job::class)->states('full')->create();
        $jobFromThisWeek = factory(Job::class)->states('full')->create(['created_at' => Carbon::parse('-3 days')]);
        $jobFromThisMonth = factory(Job::class)->states('full')->create(['created_at' => Carbon::parse('-14 days')]);

        $response = $this->get('/');

        $this->assertTrue($response->data('groups')['Today']->contains($jobFromToday));
        $this->assertTrue($response->data('groups')['This Week']->contains($jobFromThisWeek));
        $this->assertTrue($response->data('groups')['This Month']->contains($jobFromThisMonth));
    }

    /** @test */
    public function jobs_are_displayed_in_descending_order() 
    {
        $jobA = factory(Job::class)->states('full')->create(['created_at' => Carbon::parse('-2 minutes')]);
        $jobB = factory(Job::class)->states('full')->create(['created_at' => Carbon::parse('-3 minutes')]);
        $jobC = factory(Job::class)->states('full')->create(['created_at' => Carbon::parse('-1 minute')]);
        
        $response = $this->get('/');

        $this->assertEquals([
            $jobC->id,
            $jobA->id,
            $jobB->id,
        ], $response->data('groups')['Today']->pluck('id')->values()->all());
    }

    /** @test */
    public function featured_jobs_appear_before_regular_jobs()
    {
        $notFeaturedJob = factory(Job::class)->states('full')->create();
        $featuredJob = factory(Job::class)->states('full')->create([ 'featured' => 1 ]);
        $anotherNotFeaturedJob = factory(Job::class)->states('full')->create();
        $anotherFeaturedJob = factory(Job::class)->states('full')->create([ 'featured' => 1 ]);
        
        $response = $this->get('/');

        $this->assertTrue($response->data('groups')['Featured']->contains($featuredJob));
        $this->assertTrue($response->data('groups')['Featured']->contains($anotherFeaturedJob));

        $this->assertFalse($response->data('groups')['Featured']->contains($notFeaturedJob));
        $this->assertFalse($response->data('groups')['Featured']->contains($anotherNotFeaturedJob));
    }

    /** @test */
    public function a_user_can_view_a_single_published_job()
    {
        $job = factory(Job::class)->states('full')->create();

        $response = $this->get("/job/{$job->id}");
        
        $response->assertStatus(200);
        $response->assertViewHas('job', $job->fresh());
    }

    /** @test */
    public function a_user_cannot_view_a_single_unpublished_job()
    {
        $job = factory(Job::class)->states('unpublished')->create();
        
        $response = $this->get("/job/{$job->id}");

        $response->assertRedirect('/');
    }

    /** @test */
    public function an_administrator_can_see_a_single_unpublished_job()
    {
        $admin = $this->createAdminUser();
        $job = factory(Job::class)->states('unpublished')->create();

        $response = $this->actingAs($admin)->get("/job/{$job->id}");
        
        $response->assertStatus(200);
        $response->assertViewHas('job', $job->fresh());
    }

    /** @test */
    public function a_user_cant_view_a_job_that_does_not_exist()
    {
        $response = $this->withExceptionHandling()->get("/job/1");

        $response->assertStatus(404);
    }

    /** @test */
    public function a_user_can_filter_jobs_by_type()
    {
        $fulltimeJob = factory(Job::class)->states('full')->create(['type' => 'Full Time']);
        $anotherFulltimeJob = factory(Job::class)->states('full')->create(['type' => 'Full Time']);
        $parttimeJob = factory(Job::class)->states('full')->create(['type' => 'Part Time']);

        $response = $this->get('/?filter=full-time');

        $this->assertTrue($response->data('groups')['Today']->contains($fulltimeJob));
        $this->assertTrue($response->data('groups')['Today']->contains($anotherFulltimeJob));
        $this->assertFalse($response->data('groups')['Today']->contains($parttimeJob));


        $response = $this->get('/?filter=part-time');
        
        $this->assertTrue($response->data('groups')['Today']->contains($parttimeJob));
        $this->assertFalse($response->data('groups')['Today']->contains($fulltimeJob));
        $this->assertFalse($response->data('groups')['Today']->contains($anotherFulltimeJob));

    }

}
