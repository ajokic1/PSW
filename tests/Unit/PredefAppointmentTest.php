<?php

namespace Tests\Unit;

use App\Appointment;
use App\Clinic;
use App\PredefAppointment;
use App\Services\AppointmentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\LoginAsPatient;
use Tests\TestCase;

class PredefAppointmentTest extends TestCase
{
    use LoginAsPatient;
    use RefreshDatabase;

    /** @test */
    public function index_returns_all_predefined_appointments_for_clinic() {
        factory(Clinic::class, 2)->create();
        factory(PredefAppointment::class, 3)->create([
            'clinic_id' => 1,
            'doctor_id' => 1,
            'appointment_type_id' => 1,
        ]);
        factory(PredefAppointment::class)->create([
            'clinic_id' => 2,
            'doctor_id' => 1,
            'appointment_type_id' => 1,
        ]);

        $this->get('/api/clinics/1/predef')->assertJsonCount(3);
        $this->get('/api/clinics/2/predef')->assertJsonCount(1);
    }

    /** @test */
    public function store_creates_appointment_if_available() {
        $predef = factory(PredefAppointment::class)->create([
            'clinic_id' => 1,
            'doctor_id' => 1,
            'appointment_type_id' => 1,
        ]);
        $this->partialMock(AppointmentService::class, function($mock) {
            $mock->shouldReceive('isAvailable')
                ->once()
                ->andReturn(true);
        });
        $this->post('/api/predef/1')->assertStatus(201);

        $this->assertDeleted($predef);
        $appointment = Appointment::first();
        self::assertTrue(!is_null($appointment));
    }

}
