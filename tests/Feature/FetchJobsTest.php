<?php

namespace Tests\Feature;

use \Mockery;
use App\JobGateway;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FetchJobsTest extends TestCase
{

    public function tearDown() 
    {
        parent::tearDown();

        Mockery::close();
    }

    /** @test */
    public function a_command_can_fetch_jobs()
    {
        $jobGateway = Mockery::spy(JobGateway::class);

        $this->app->instance(JobGateway::class, $jobGateway);

        $ageInDays = 1;

        $this->artisan('jobs:fetch', [
            '--days' => $ageInDays,
            '--keywords' => ['testkeyword', 'anotherkeyword']
        ]);
    
        $jobGateway->shouldHaveReceived('filterByAgeInDays')->with($ageInDays)->once();    
        $jobGateway->shouldHaveReceived('fetch')->once();
        $jobGateway->shouldHaveReceived('save')->once();
    }

}
