<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RegisterTest extends TestCase
{
    use DatabaseTransactions;

    public function testRegister()
    {
        $this->visit('/register')
            ->type('example@com', 'email')
            ->type('123456', 'password')
            ->press('Create Account')
            ->seePageIs('/')
            ->see('My Profile');
    }  

    public function testRegisterFailure()
    {
        $user = factory(App\User::class)->create();

        $this->visit('/register')
            ->type($user->email, 'email')
            ->type('123456', 'password')
            ->press('Create Account')
            ->seePageIs('/register')
            ->see('Email has been registered');
    } 
}
