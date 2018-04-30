<?php 

namespace App;

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
        foreach ($jobs as $job) {
            DB::table('jobs')->insert([
                'title' => $job['title'],
                'description' => $job['description'],
                'company' => array_key_exists('company', $job) ? $job['company'] : null,
                'company_logo' => (array_key_exists('hiringOrganization', $job) && array_key_exists('logo', $job['hiringOrganization'])) ? $job['hiringOrganization']['logo'] : null,
                'location' => array_key_exists('location', $job) ? $job['location'] : null,
                'salary' => array_key_exists('baseSalary', $job) ? $job['baseSalary'] : null,
                'type' => array_key_exists('workHours', $job) ? $job['workHours'] : null,
                'apply_url' => $job['url'],
            ]);
        }
    }

    public function filterByKeyword($keyword = '') 
    {
        $this->client->setKeyword($keyword);

        return $this;
    }
}
