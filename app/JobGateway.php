<?php 

namespace App;

use JobApis\Jobs\Client\JobsMulti;

class JobGateway 
{
    protected $client;

    public function __construct() 
    {
        $providers = config('services.jobs');

        $this->client = new JobsMulti($providers);
    }

    public function fetch() 
    {
        return $this->client->getAllJobs();
    }

    public function filterByKeyword($keyword = '') 
    {
        $this->client->setKeyword($keyword);
        return $this;
    }

    public function filterByAgeInDays($days = 90) 
    {
        $this->client->setOptions([ 'maxAge' => $days ]);

        return $this;
    }

    public function client() 
    {
        return $this->client;
    }

}
