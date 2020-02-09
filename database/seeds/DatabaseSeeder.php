<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UsersTableSeeder::class,
            ClinicsTableSeeder::class,
            DoctorsTableSeeder::class,
            ConditionsTableSeeder::class,
            ClinicRatingsTableSeeder::class,
            DoctorRatingsTableSeeder::class,
            AppointmentTypesTableSeeder::class,
            AppointmentsTableSeeder::class,

        ]);
    }
}
