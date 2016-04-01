<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'email' => $faker->email,
        'password' => password_hash('123456', PASSWORD_BCRYPT)
    ];
});

$factory->define(App\Profile::class, function () {
    return [

    ];
});

$factory->define(App\Models\Subscriber::class, function (Faker\Generator $faker) {
    return [
        'openId'        => '1234567',
    ];
});
