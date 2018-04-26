<?php 

namespace App;

use JobApis\Jobs\Client\JobsMulti;
use JobApis\Jobs\Client\Collection as JobCollection;

class JobGateway 
{
    protected $client;
    protected $keywords = [];

    public function __construct() 
    {
        $providers = config('services.jobs');

        $this->client = new JobsMulti($providers);
    }

    public function client() 
    {
        return $this->client;
    }

    public function fetch() 
    {
        if (empty($this->keywords)) {
            return $this->client->getAllJobs();
        }

        $jobs = new JobCollection;

        foreach ($this->keywords as $keyword) {
            $this->filterByKeyword($keyword);
            $jobs->addCollection($this->client->getAllJobs());
        }

        return $jobs;
    }

    public function filterByAgeInDays($days = 90) 
    {
        $this->client->setOptions([ 'maxAge' => $days ]);

        return $this;
    }

    public function filterByKeywords($keywords = []) 
    {
        $this->keywords = $keywords;
    }

    public function filterByKeyword($keyword = '') 
    {
        $this->client->setKeyword($keyword);
        return $this;
    }
}
