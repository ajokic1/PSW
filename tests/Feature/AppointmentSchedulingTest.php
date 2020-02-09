<?php

namespace Tests\Feature;

use App\Appointment;
use App\AppointmentType;
use App\Clinic;
use App\Doctor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Tests\LoginAsPatient;
use Tests\TestCase;

class AppointmentSchedulingTest extends TestCase
{
    use RefreshDatabase;
    use LoginAsPatient;

    /** @test */
    public function patient_can_schedule_appointment_and_see_list() {
        Mail::fake();

        $clinic = factory(Clinic::class)->create();
        factory(Doctor::class)->create()->clinics()->attach($clinic->id, ['works_from'=>'08:00:00', 'works_to'=>'16:00:00']);
        factory(AppointmentType::class)->create();

        $appointment = $this->makeAppointmentForUser(Auth::id(), '2020-10-10');
        $this->post('/api/appointments', $appointment->toArray());
        $appointment=Appointment::first();
        $this->assertTrue(Appointment::count() == 1);
        $this->assertTrue($appointment->accepted == false);

        //patient accepts the appointment
        $this->get('/api/appointments/' . $appointment->id . '/accept/' . $appointment->token)->assertOk();
        $this->assertTrue(Appointment::first()->accepted == true);

        //patient gets list of appointments
        $this->get('/api/user/'. Auth::id() .'/appointments/')
            ->assertJson([Appointment::first()->toArray()]);
    }

    public function patient_can_schedule_appointment_and_cancel_it() {
        Mail::fake();

        $clinic = factory(Clinic::class)->create();
        factory(Doctor::class)->create()->clinics()->attach($clinic->id, ['works_from'=>'08:00:00', 'works_to'=>'16:00:00']);
        factory(AppointmentType::class)->create();

        $appointment = $this->makeAppointmentForUser(Auth::id(), '2020-10-10');
        $this->post('/api/appointments', $appointment->toArray());
        $appointment=Appointment::first();
        $this->assertTrue(Appointment::count() == 1);

        //patient cancels the appointment
        $this->delete('/api/appointments/' . $appointment->id)->assertOk();

        //patient gets list of appointments
        $this->get('/api/user/'. Auth::id() .'/appointments/')
            ->assertJsonCount(0);
    }

    /** @test */
    public function patient_cannot_schedule_appointment_if_unavailable() {
        Mail::fake();

        $clinic = factory(Clinic::class)->create();
        factory(Doctor::class)->create()->clinics()->attach($clinic->id, ['works_from'=>'08:00:00', 'works_to'=>'16:00:00']);
        factory(AppointmentType::class)->create();

        $appointment = $this->makeAppointmentForUser(Auth::id(), '2020-10-10', '09:00:00');
        $this->post('/api/appointments', $appointment->toArray());
        $appointment = $this->makeAppointmentForUser(Auth::id(), '2020-10-10', '09:00:00');
        $this->post('/api/appointments', $appointment->toArray())->assertStatus(400);
        $this->assertTrue(Appointment::count()==1);

    }

    // =================================================================================================================

    private function makeAppointmentForUser($userId, $date=null, $time=null) {
        return factory(Appointment::class)->make($this->appointmentAdditionalData($userId, $date, $time));
    }

    private function appointmentAdditionalData($userId, $date, $time) {
        $data = [
            'user_id' => $userId,
            'doctor_id' => 1,
            'clinic_id' => 1,
            'appointment_type_id' => 1
        ];
        if (!is_null($date)) {$data['date'] = $date;}
        if (!is_null($time)) {$data['time'] = $time;}

        return $data;

    }
}
