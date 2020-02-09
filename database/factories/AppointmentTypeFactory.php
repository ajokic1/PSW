<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\AppointmentType;
use Faker\Generator as Faker;

$factory->define(AppointmentType::class, function (Faker $faker) {
    return [
        'type' => $faker->randomElement(['pregled', 'snimanje', 'analiza', 'operacija']),
        'name' => $faker->words(2, true),
        'duration' => $faker->randomElement(['00:15:00', '00:20:00', '00:30:00', '00:45:00', '01:00:00']),
    ];
});

