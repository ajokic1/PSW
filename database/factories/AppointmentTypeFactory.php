<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\AppointmentType;
use Faker\Generator as Faker;

$factory->define(AppointmentType::class, function (Faker $faker) {
    return [
        'type' => $faker->randomElement(['pregled', 'snimanje', 'analiza', 'operacija']),
        'name' => $faker->word,
        'duration' => $faker->time('H:i:s', '01:00:00'),
    ];
});

