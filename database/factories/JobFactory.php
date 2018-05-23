<?php

use App\Job;
use App\Customer;
use Faker\Generator as Faker;

$factory->define(Job::class, function (Faker $faker) {
    return [
        'title' => $faker->jobTitle,
        'description' => $faker->text(1000),
        'apply_url' => 'http://example.com',
        'customer_id' => function() {
            return factory(Customer::class)->create()->id;
        }
    ];
});

$factory->state(Job::class, 'full', function (Faker $faker) {
    return [
        'description' => $faker->text(1000),
        'company' => $faker->company,
        'company_logo' => 'http://via.placeholder.com/100x100',
        'location' => $faker->city,
        'salary' => '$110k',
        'type' => 'Full Time',
        'published' => 1
    ];
});

$factory->state(Job::class, 'unpublished', function (Faker $faker) {
    return [
        'description' => $faker->text(1000),
        'company' => $faker->company,
        'company_logo' => 'http://via.placeholder.com/100x100',
        'location' => $faker->city,
        'salary' => '$110k',
        'type' => 'Full Time',
        'published' => 0
    ];
});
