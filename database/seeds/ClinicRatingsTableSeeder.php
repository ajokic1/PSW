<?php

use Illuminate\Database\Seeder;

class ClinicRatingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = \App\User::all();
        $clinics = \App\Clinic::all();
        foreach ($users as $user) {
            $randomClinics = $clinics->random(rand(3,6));
            foreach ($randomClinics as $clinic) {
                factory(\App\ClinicRating::class)->create([
                    'user_id' => $user->id,
                    'clinic_id' => $clinic->id
                ]);
            }

        }
    }
}
