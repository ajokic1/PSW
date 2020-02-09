<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\ClinicRating;
use Faker\Generator as Faker;

$factory->define(ClinicRating::class, function (Faker $faker) {
    return [
        'rating' => $faker->numberBetween(1, 5),
        'comment' => $faker->sentence(6, true),
    ];
});
