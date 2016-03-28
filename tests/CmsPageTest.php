<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CmsPageTest extends TestCase
{
    use DatabaseTransactions;

    public function testUpdateName()
    {
        $user = factory(App\User::class)->create();

        $this->actingAs($user)
            ->visit('/')
            ->see('My Profile');
    }    
}
