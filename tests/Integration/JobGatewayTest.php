<?php

namespace Tests\Integration;

use App\JobGateway;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class JobGatewayTest extends TestCase
{
    protected $gateway;

    public function setUp() 
    {
        parent::setUp();
        
        $this->gateway = new JobGateway;
    }
    
    /** @test */
    public function it_can_fetch_jobs()
    {
        $jobs = $this->gateway->fetch();

        $this->assertInstanceOf('JobApis\Jobs\Client\Collection', $jobs);
    }

    /** @test */
    public function it_can_filter_jobs()
    {
        
    }

    /** @test */
    public function it_can_add_jobs_to_the_db()
    {
        
    }

}
