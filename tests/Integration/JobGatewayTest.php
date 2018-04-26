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
    public function it_can_filter_jobs_by_keyword()
    {
        $keyword = 'test keyword';

        $this->assertNull($this->getProtectedProperty($this->gateway->client(), 'keyword'));

        $this->gateway->filterByKeyword($keyword);

        $this->assertEquals($this->getProtectedProperty($this->gateway->client(), 'keyword'), $keyword);
    }

    /** @test */
    public function it_can_filter_jobs_by_age()
    {
        $ageInDays = 1;

        $this->assertNull($this->getProtectedProperty($this->gateway->client(), 'maxAge'));

        $this->gateway->filterByAgeInDays($ageInDays);

        $this->assertEquals($this->getProtectedProperty($this->gateway->client(), 'maxAge'), $ageInDays);
    }

    private function getProtectedProperty($object, $property = null)
    {
        $class = new \ReflectionClass(get_class($object));
        $property = $class->getProperty($property);
        $property->setAccessible(true);

        return $property->getValue($object);
    }

}
