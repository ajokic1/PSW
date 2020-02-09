<?php

use Illuminate\Database\Seeder;

class DoctorRatingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = \App\User::all();
        $doctors = \App\Doctor::all();
        foreach ($users as $user) {
            $randomDoctors = $doctors->random(rand(3,6));
            foreach ($randomDoctors as $doctor) {
                factory(\App\DoctorRating::class)->create([
                    'user_id' => $user->id,
                    'doctor_id' => $doctor->id
                ]);
            }

        }
    }
}
