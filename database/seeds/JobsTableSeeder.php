<?php

use App\Job;
use Illuminate\Database\Seeder;

class JobsTableSeeder extends Seeder
{
    
    public function run()
    {
        factory(Job::class, 5)->states('full')->create();    

        factory(Job::class)->states('full')->create([
            'company' => null
        ]);

        factory(Job::class)->states('full')->create([
            'company_logo' => null
        ]);

        factory(Job::class)->states('full')->create([
            'location' => null
        ]);

        factory(Job::class)->states('full')->create([
            'salary' => null
        ]);

        factory(Job::class)->states('full')->create([
            'type' => null
        ]);

    }

}
