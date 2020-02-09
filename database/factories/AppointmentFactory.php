<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Appointment;
use Faker\Generator as Faker;

$factory->define(Appointment::class, function (Faker $faker) {
    return [
        'date' => $faker->dateTimeBetween('-10 days', '+10 days')->format('Y-m-d'),
        'time' => $faker->randomElement([
            '09:00:00','10:00:00', '10:30:00', '11:00:00', '12:00:00', '12:30:00', '14:00:00']),
        'accepted' => true,
        'token' => $faker->lexify('??????????')
    ];


});
