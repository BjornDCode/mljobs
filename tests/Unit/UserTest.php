<?php

namespace Tests\Unit;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_knows_if_its_an_administrator()
    {
        $admin = factory(User::class)->create();
        $nonAdmin = factory(User::class)->create();  

        config([ 'app.administrators' => [ $admin->email ] ]);

        $this->assertTrue($admin->isAdmin());
        $this->assertFalse($nonAdmin->isAdmin());
    }

}
