<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LoginTest extends TestCase
{
    use DatabaseTransactions;

    public function testLogin()
    {
        $user = factory(App\User::class)->create();

        $this->visit('/login')
            ->type($user->email, 'email')
            ->type('123456', 'password')
            ->press('LOG IN')
            ->seePageIs('/')
            ->see('My Profile');
    }

    public function testLoginFailure()
    {
        $user = factory(App\User::class)->create();

        $this->visit('/login')
            ->type($user->email, 'email')
            ->type('000000', 'password')
            ->press('LOG IN')
            ->seePageIs('/login')
            ->see('Incorrect password');
    }
}
