<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Appointment;
use Faker\Generator as Faker;

$factory->define(Appointment::class, function (Faker $faker) {
    return [
        'date' => $faker->date(),
        'time' => $faker->time(),
        'token' => $faker->lexify('??????????')
    ];


});
