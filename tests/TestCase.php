<?php

namespace Tests;

use App\User;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp() 
    {
        parent::setUp();

        TestResponse::macro('data', function($key) {
            return $this->original->getData()[$key];
        });

        $this->withoutExceptionHandling();
    }

    protected function createAdminUser()
    {
        $admin = factory(User::class)->create();
        config([ 'app.administrators' => [ $admin->email ] ]);
        return $admin;
    }

}
