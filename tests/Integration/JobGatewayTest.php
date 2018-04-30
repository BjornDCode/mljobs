<?php

namespace Tests\Integration;

use App\JobGateway;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class JobGatewayTest extends TestCase
{
    use RefreshDatabase;

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
    public function it_can_filter_jobs_by_a_keyword()
    {
        $keyword = 'test keyword';

        $this->assertNull($this->getProtectedProperty($this->gateway->client(), 'keyword'));

        $this->gateway->filterByKeyword($keyword);

        $this->assertEquals($this->getProtectedProperty($this->gateway->client(), 'keyword'), $keyword);
    }

        /** @test */
    public function it_can_filter_by_an_array_of_keywords()
    {
        $keywords = ['keyword1', 'keyword2', 'anotherkeyword'];

        $this->assertEquals([], $this->getProtectedProperty($this->gateway, 'keywords'));

        $this->gateway->filterByKeywords($keywords);

        $this->assertEquals($keywords, $this->getProtectedProperty($this->gateway, 'keywords'));
    }

    /** @test */
    public function it_can_filter_jobs_by_age()
    {
        $ageInDays = 1;

        $this->assertNull($this->getProtectedProperty($this->gateway->client(), 'maxAge'));

        $this->gateway->filterByAgeInDays($ageInDays);

        $this->assertEquals($this->getProtectedProperty($this->gateway->client(), 'maxAge'), $ageInDays);
    }

    /** @test */
    public function it_can_save_jobs_to_the_database()
    {
        // Modelled after: https://github.com/jobapis/jobs-common/blob/master/src/Job.php
        $firstJob = [
            'title' => 'First Job',
            'description' => 'This is a machine learning job',
            'company' => 'Hiring Company',
            'hiringOrganization' => [ 'logo' => 'http://example.com/company/logo.jpg' ],
            'location' => 'Toledo, Mexicon',
            'url' => 'http://example.com/apply'
        ];
        $secondJob = [
            'title' => 'Second Job',
            'description' => 'This is a machine learning job',
            'baseSalary' => '100',
            'salaryCurrency' => 'USD',
            'workHours' => 'Full Time',
            'url' => 'http://example.com/apply'
        ];

        $this->gateway->save([
            $firstJob, 
            $secondJob
        ]);

        $this->assertDatabaseHas('jobs', [
            'title' => 'First Job'
        ]);
        $this->assertDatabaseHas('jobs', [
            'title' => 'Second Job'
        ]);
    }

    private function getProtectedProperty($object, $property = null)
    {
        $class = new \ReflectionClass(get_class($object));
        $property = $class->getProperty($property);
        $property->setAccessible(true);

        return $property->getValue($object);
    }

}
