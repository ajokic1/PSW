<?php

namespace Tests\Unit;

use App\Condition;
use App\Diagnosis;
use App\Doctor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\LoginAsPatient;

class DiagnosisTest extends TestCase
{
    use RefreshDatabase;
    use LoginAsPatient;

    /** @test */
    public function index_lists_all_diagnoses() {
        $doctor = factory(Doctor::class)->create();
        $condition = factory(Condition::class)->create();
        $diagnoses = factory(Diagnosis::class, 2)->create([
            'user_id' => 1,
            'doctor_id' => 1,
            'condition_id' => 1,
            'appointment_id' => 1
        ]);
        $this->json('GET', '/api/diagnoses')->assertJsonCount($diagnoses->count());
    }

    /** @test */
    public function show_returns_diagnosis_with_doctor_and_condition() {
        $doctor = factory(Doctor::class)->create();
        $condition = factory(Condition::class)->create();
        $diagnosis = factory(Diagnosis::class)->create([
            'user_id' => 1,
            'doctor_id' => 1,
            'condition_id' => 1,
            'appointment_id' => 1
        ]);
        $response = $this->get('/api/diagnoses/' . $diagnosis->id);
        $this->assertTrue($response->getOriginalContent()->toArray()[0]['doctor']['id'] == $doctor->id);
        $this->assertTrue($response->getOriginalContent()->toArray()[0]['condition']['id'] == $condition->id);
    }
}
