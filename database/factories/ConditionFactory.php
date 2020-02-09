<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Condition;
use Faker\Generator as Faker;

$factory->define(Condition::class, function (Faker $faker) {
    return [
        'name' => $faker->words(2, true),
        'type' => $faker->randomElement(['acute', 'chronic']),
        'description' => $faker->sentences(3, true),
        'sugg_therapy' => $faker->sentences(3, true),
        'symptoms' => $faker->sentences(3, true),
        'prognosis' => $faker->sentences(3, true),
    ];
});
