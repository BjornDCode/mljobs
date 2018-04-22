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
        $this->client->setKeyword('machine learning');
    }

    public function fetch() 
    {
        return $this->client->getAllJobs();
    }

}
