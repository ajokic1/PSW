<?php

namespace Tests\Unit;

use App\Appointment;
use App\AppointmentType;
use App\Clinic;
use App\Doctor;
use App\DoctorRating;
use App\Http\Controllers\DoctorController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\LoginAsAdmin;

class DoctorTest extends TestCase
{
    use RefreshDatabase;
    use LoginAsAdmin;

    /** @test */
    public function get_appointment_types() {
        $doctor = factory(Doctor::class)->create();
        $apptype = factory(AppointmentType::class)->create();
        $doctor->appointment_types()->attach($apptype, ['price'=>30]);
        $this->assertEquals($doctor->appointment_types, [$apptype->id]);
    }

    /** @test */
    public function get_rating() {
        $doctor = factory(Doctor::class)->create();
        factory(DoctorRating::class)->create(['rating' => '5', 'user_id' => '1', 'doctor_id' => '1']);
        factory(DoctorRating::class)->create(['rating' => '3', 'user_id' => '1', 'doctor_id' => '1']);
        $this->assertEquals(4, $doctor->rating);
    }

    /** @test */
    public function availability() {
        $doctor = factory(Doctor::class)->create();
        $clinic = factory(Clinic::class)->create();
        $doctor->clinics()->attach($clinic, [
            'works_from' => '08:00:00',
            'works_to' => '10:00:00']);
        $date = '2019-12-15';
        $time = '09:00:00';
        $duration = '00:30:00';
        factory(AppointmentType::class)->create(['duration'=>$duration]);
        factory(Appointment::class)->create([
            'clinic_id' => 1,
            'doctor_id' => 1,
            'appointment_type_id' => 1,
            'user_id' => 1,
            'date' => $date,
            'time' => $time
        ]);
        $available_intervals = app(DoctorController::class)->availability($clinic, $doctor, $date, $duration);
        $this->assertEquals(
            [
                [strtotime('08:00:00',0), strtotime('09:00:00',0)],
                [strtotime('09:30:00',0), strtotime('10:00:00',0)]
            ],
            $available_intervals);
    }
}
