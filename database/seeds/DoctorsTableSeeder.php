<?php

use App\Doctor;
use Illuminate\Database\Seeder;

class DoctorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $doctors = factory(Doctor::class, 50)->create();
        $clinics = \App\Clinic::all();
        // Attach a random number of random doctors to each clinic
        foreach ($clinics as $clinic) {
            $randomDoctors = $doctors->random(rand(2,10));
            foreach($randomDoctors as $doctor) {
                $clinic->doctors()->attach($doctor->id, ['works_from' => '08:00:00', 'works_to'=>'16:00:00']);
            }
        }
    }
}
