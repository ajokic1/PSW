<?php

use App\Events\AppointmentAccepted;
use Illuminate\Database\Seeder;

class AppointmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $doctors = \App\Doctor::all();
        $clinics = \App\Clinic::all();
        $types = \App\AppointmentType::all();
        $users = \App\User::all();
        $appointments = [];
        for($i=0; $i<150; $i++) {
            $randomClinic = $clinics->random();
            array_push($appointments, factory(\App\Appointment::class)->create([
                'user_id' => $users->random()->id,
                'appointment_type_id' => $types->random()->id,
                'doctor_id' => $randomClinic->doctors()->get()->random()->id,
                'clinic_id' => $randomClinic->id
            ]));
            //Create some predef appointments
            factory(\App\PredefAppointment::class)->create([
                'appointment_type_id' => $types->random()->id,
                'doctor_id' => $randomClinic->doctors()->get()->random()->id,
                'clinic_id' => $randomClinic->id
            ]);
        }

        //Calculate availabilities
        foreach ($appointments as $appointment) {
            event(new AppointmentAccepted($appointment));
        }
    }
}
