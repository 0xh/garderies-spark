<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\User::class, function (Faker $faker) {
    return [
        'name'              => $faker->firstNameFemale . ' ' . $faker->lastName,
        'email'             => $faker->unique()->safeEmail,
        'phone'             => '+41 79 ' . rand(300, 500) . ' ' . rand(20, 90) . ' ' . rand(20, 90),
        'password'          => bcrypt('123456'), // secret
        'remember_token'    => str_random(10),
    ];
});
