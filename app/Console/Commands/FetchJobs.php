<?php

namespace App\Console\Commands;

use App\JobGateway;
use Illuminate\Console\Command;

class FetchJobs extends Command
{

    protected $gateway;
    protected $signature = 'jobs:fetch {--days=1} {--keywords=*}';
    protected $description = 'Fetch jobs with the JobGateway
                            {--days : The maxAge of the jobs retrieved}
                            {--keywords : An array of keywords to search for}';
    

    public function __construct(JobGateway $gateway)
    {
        parent::__construct();

        $this->gateway = $gateway;
    }

    public function handle()
    {
        $this->gateway->filterByKeywords($this->option('keywords'));
        $this->gateway->filterByAgeInDays($this->option('days'));
        $jobs = $this->gateway->fetch();

        $this->gateway->save($jobs);
    }
}
