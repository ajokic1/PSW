<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Diagnosis;
use Faker\Generator as Faker;

$factory->define(Diagnosis::class, function (Faker $faker) {
    return [
        'details' => $faker->sentences(3, true),
        'therapy' => $faker->sentences(3, true),
    ];
});
