<?php 

namespace App;

use App\Job;
use Illuminate\Support\Facades\DB;
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

        return $this;
    }

    public function save($jobs) 
    {
        foreach ($jobs->all() as $job) {
            Job::create([
                'title' => $job->title,
                'description' => $job->description,
                'company' => $job->getCompanyName(),
                'company_logo' => $job->getCompanyLogo(),
                'location' => $job->getLocation(),
                'salary' => $job->baseSalary,
                'type' => $job->workHours,
                'apply_url' => $job->url,
            ]);
        }
    }

    public function filterByKeyword($keyword = '') 
    {
        $this->client->setKeyword($keyword);

        return $this;
    }
}
