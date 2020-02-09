<?php

use Illuminate\Database\Seeder;

class AppointmentTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = factory(\App\AppointmentType::class, 15)->create();
        $doctors = \App\Doctor::all();

        foreach ($doctors as $doctor) {
            $doctor->appointment_types()->attach($types->random(), ['price'=>rand(2,10)*10]);
            $doctor->appointment_types()->attach($types->random(), ['price'=>rand(2,10)*10]);
        }
    }
}
