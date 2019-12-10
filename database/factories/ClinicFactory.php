<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Clinic;
use Faker\Factory;
use Faker\Generator as Faker;

$factory->define(Clinic::class, function (Faker $faker) {
    $fakerSr = Factory::create('sr_Latn_RS');
    $clinicNames=[
        'Care & Cure Hospital',
        'Medwin Cares',
        'Remedy plus care',
        'Flowerence ',
        'Rejuvenate',
        'Health Object  Hospital',
        'Mankind Medicare',
        'Delight Sun CLinic',
        'Ultra Care House',
        'Ace hospital',
        'HealthyWave',
        'NorthMark',
        'Hestinn Hospital',
        'wellHue Hospital',
        'BioNeu care',
        'GoodAce care',
        'HealthStreet',
        'Good Planet',
        'YouHeal Hospital',
        'Redstar Hospital',
        'True life care',
    ];
    $clinicPhotos=[
        'clinic3.jpg',
        'clinic5.jpg',
        'clinic.jpg',
        'clinic2.jpg',
        'clinic4.jpg',
        'clinic6.jpg',
    ];
    $cities=[
        'Novi Sad',
        'Beograd',
    ];
    return [
        'name' => $faker->randomElement($clinicNames),
        'description' => $faker->sentence($nbWords = 10, $variableNbWords = true),
        'photo' => $faker->randomElement($clinicPhotos),
        'address' => $fakerSr->streetAddress,
        'city' => $faker->randomElement($cities),
        'country' => 'Srbija',
    ];
});

