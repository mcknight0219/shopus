<?php

use App\Profile;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Symfony\Component\HttpFoundation\File\UploadedFile as SymfonyUploadedFile;

class EditProfileTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test profile update logic
     *
     * @return void
     */
    public function testEditProfile()
    {
        // Mock the process empty profile is created upon creating new user
        $user = factory(App\User::class)->create();
        $profile = factory(App\Profile::class)->create([
            'user_id' => $user->id
        ]);

        $this->actingAs($user)->json('POST', '/profile/edit', [
                'firstName' => 'first name',
                'lastName'  => 'last name',
                'country'   => 'canada',
                'city'      => 'toronto'  
            ], ['HTTP_X-Requested-With' => 'XMLHttpRequest']
        )->seeJson(['status' => 'ok']);

        $updated = Profile::where('user_id', $user->id)->first();

        $this->assertEquals('first name',   $updated->firstName);
        $this->assertEquals('last name',    $updated->lastName);
        $this->assertEquals('canada',       $updated->country);
        $this->assertEquals('toronto',      $updated->city);
    }
}
