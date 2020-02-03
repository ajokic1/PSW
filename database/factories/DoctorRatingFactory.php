<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\DoctorRating;
use Faker\Generator as Faker;

$factory->define(DoctorRating::class, function (Faker $faker) {
    return [
        'rating' => $faker->numberBetween(1,5),
    ];
});
