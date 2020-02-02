<?php

namespace Tests\Unit;

use App\Appointment;
use App\Clinic;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\LoginAsAdmin;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;


class ClinicTest extends TestCase
{
    use RefreshDatabase;
    use LoginAsAdmin;

    /** @test */
    public function index_returns_all_clinics() {
        $clinics = factory(Clinic::class, 2)->create();
        $response = $this->json('GET','/api/clinics');
        $response->assertJsonCount(2);
    }

    /** @test */
    public function store_creates_clinic() {
        $clinic = factory(Clinic::class)->make();
        $this->post('/api/clinics', $clinic->toArray())->assertCreated();
    }

    /** @test */
    public function store_validates_data() {
        $clinic = [
            'name' => 'asdf',
            'address' => 'asdf',
            'city' => 'asdf'
        ]; // Missing country
        $this->post('/api/clinics', $clinic)->assertSessionHasErrors();
    }

    /** @test */
    public function show_returns_clinic_with_doctors_cannot_rate() {
        $clinic = factory(Clinic::class)->create();
        $response = $this->json('GET', '/api/clinics/1');
        $response->assertJson($clinic->toArray());
        $this->assertEquals(false, $response['canRate']);
        $this->assertArrayHasKey('doctors', $response);
    }

    /** @test */
    public function show_can_rate_if_clinic_and_user_have_appointments() {
        $clinic = factory(Clinic::class)->create();
        $clinic->appointments()->save(factory(Appointment::class)->make([
            'user_id' => 1,
            'clinic_id' => 1,
            'doctor_id' => 1,
            'appointment_type_id' => 1
        ]));

        $response = $this->json('GET', 'api/clinics/1');
        $this->assertEquals(true, $response['canRate']);
    }

    /** @test */
    public function update_updates_data() {
        $clinic = factory(Clinic::class)->create();
        $clinic['name'] = 'updated';
        $this->put('/api/clinics/1', $clinic->toArray());
        $clinic = Clinic::find(1);
        $this->assertEquals('updated', $clinic['name']);

    }

    /** @test */
    public function delete_deletes_entry() {
        $clinic = factory(Clinic::class)->create();
        $this->delete('/api/clinics/1');
        $this->assertDeleted($clinic);
    }
}
