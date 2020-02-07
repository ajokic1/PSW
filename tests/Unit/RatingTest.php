<?php

namespace Tests\Unit;

use App\Appointment;
use App\Clinic;
use App\ClinicRating;
use App\Doctor;
use App\DoctorRating;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\LoginAsPatient;
use Tests\TestCase;

class RatingTest extends TestCase
{
    use RefreshDatabase;
    use LoginAsPatient;

    /** @test */
    public function rate_clinic_adds_to_ratings_table() {
        $clinic = factory(Clinic::class)->create();
        factory(Appointment::class)->create([
            'user_id' => 1,
            'clinic_id' => 1,
            'doctor_id' => 1,
            'appointment_type_id' => 1
        ]);
        $this->post('/api/clinics/ ' . $clinic->id . '/rate', ['rating' => 5]);
        $rating = ClinicRating::first();
        $this->assertTrue($rating->rating == 5);
    }

    /** @test */
    public function rate_clinic_twice_updates_rating() {
        $clinic = factory(Clinic::class)->create();
        factory(Appointment::class)->create([
            'user_id' => 1,
            'clinic_id' => 1,
            'doctor_id' => 1,
            'appointment_type_id' => 1
        ]);
        $this->post('/api/clinics/ ' . $clinic->id . '/rate', ['rating' => 5]);
        $this->post('/api/clinics/ ' . $clinic->id . '/rate', ['rating' => 3]);
        $rating = ClinicRating::first();
        $this->assertTrue($rating->rating == 3);
        $this->assertCount(1, ClinicRating::all());
    }

    /** @test */
    public function clinic_rating_returns_average_rating_for_clinic() {
        factory(Clinic::class)->create();
        factory(ClinicRating::class)->create([
            'clinic_id' => 1,
            'user_id' => 1,
            'rating' => 5
        ]);
        factory(ClinicRating::class)->create([
            'clinic_id' => 1,
            'user_id' => 2,
            'rating' => 3
        ]);
        $this->get('/api/clinics/1/rating')->assertSeeText('4');
    }

    /** @test */
    public function rate_doctor_adds_to_ratings_table() {
        $doctor = factory(Doctor::class)->create();
        factory(Appointment::class)->create([
            'user_id' => 1,
            'clinic_id' => 1,
            'doctor_id' => 1,
            'appointment_type_id' => 1
        ]);
        $this->post('/api/doctors/ ' . $doctor->id . '/rate', ['rating' => 5]);
        $rating = DoctorRating::first();
        $this->assertTrue($rating->rating == 5);
    }

    /** @test */
    public function rate_doctor_twice_updates_rating() {
        $doctor = factory(Doctor::class)->create();
        factory(Appointment::class)->create([
            'user_id' => 1,
            'clinic_id' => 1,
            'doctor_id' => 1,
            'appointment_type_id' => 1
        ]);
        $this->withoutExceptionHandling();
        $this->post('/api/doctors/ ' . $doctor->id . '/rate', ['rating' => 5]);
        $this->post('/api/doctors/ ' . $doctor->id . '/rate', ['rating' => 3]);
        $rating = DoctorRating::first();
        $this->assertTrue($rating->rating == 3);
        $this->assertCount(1, DoctorRating::all());
    }

    /** @test */
    public function doctor_rating_returns_average_rating_for_clinic() {
        factory(Doctor::class)->create();
        factory(DoctorRating::class)->create([
            'doctor_id' => 1,
            'user_id' => 1,
            'rating' => 5
        ]);
        factory(DoctorRating::class)->create([
            'doctor_id' => 1,
            'user_id' => 2,
            'rating' => 3
        ]);
        $this->get('/api/doctors/1/rating')->assertSeeText('4');
    }
}
