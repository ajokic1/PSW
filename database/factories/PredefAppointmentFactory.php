<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\PredefAppointment;
use Faker\Generator as Faker;

$factory->define(PredefAppointment::class, function (Faker $faker) {
    return [
        'date' => $faker->date,
        'time' => $faker->time('H:i:s'),
    ];
});
