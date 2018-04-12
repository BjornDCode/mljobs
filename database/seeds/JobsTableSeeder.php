<?php

use App\Job;
use Illuminate\Database\Seeder;

class JobsTableSeeder extends Seeder
{
    
    public function run()
    {
        factory(Job::class, 20)->create();    
    }

}
