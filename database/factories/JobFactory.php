<?php

use App\Job;
use Faker\Generator as Faker;

$factory->define(Job::class, function (Faker $faker) {
    return [
        'title' => $faker->jobTitle,
        'description' => $faker->text(1000),
        'company' => $faker->company,
        'company_logo' => 'http://via.placeholder.com/100x100',
        'location' => $faker->city,
        'salary' => '$110k',
        'type' => 'Full Time',
        'apply_url' => 'http://example.com'
    ];
});
