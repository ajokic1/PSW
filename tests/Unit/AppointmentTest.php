<?php

namespace Tests\Unit;

use App\Appointment;
use App\AppointmentType;
use App\Availability;
use App\Clinic;
use App\Doctor;
use App\Events\AppointmentAccepted;
use App\Events\AppointmentCancelled;
use App\Listeners\AddToAvailability;
use App\Mail\AppointmentApproved;
use App\Services\AppointmentService;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Tests\LoginAsPatient;
use Tests\TestCase;
use Tests\LoginAsAdmin;

class AppointmentTest extends TestCase
{
    use RefreshDatabase;
    use LoginAsPatient;

    // =================================================================================================================
    // AppointmentController
    // =================================================================================================================


    /** @test */
    public function index_patient_cannot_get_another_patients_appointments() {
        $id = factory(User::class)->create()->id;
        $this->get('/api/user/'.$id.'/appointments')->assertStatus(401);
    }

    /** @test */
    public function index_patient_gets_list_of_own_appointments() {
        $user = factory(User::class)->create();
        $app1 = $this->createAppointmentForUser(Auth::id(), date('Y-m-d'));
        $app2 = $this->createAppointmentForUser(Auth::id(), date('Y-m-d'));
        $app3 = $this->createAppointmentForUser($user->id, date('Y-m-d'));

        $response = $this->json('GET','/api/user/'.Auth::id().'/appointments');
        $response->assertJson([$app1->toArray(), $app2->toArray()]);
    }

    /** @test */
    public function index_patient_gets_list_of_future_appointments() {
        $app1 = $this->createAppointmentForUser(Auth::id(), date('Y-m-d'));
        $app2 = $this->createAppointmentForUser(Auth::id(), date('Y-m-d'));
        $app3 = $this->createAppointmentForUser(Auth::id(), date("Y-m-d", strtotime("-5 days")));

        $response = $this->json('GET','/api/user/'.Auth::id().'/appointments');
        $response->assertJson([$app1->toArray(), $app2->toArray()]);
    }

    /** @test */
    public function index_history_patient_gets_history_of_appointments() {
        $app1 = $this->createAppointmentForUser(Auth::id(), date('Y-m-d'));
        $app2 = $this->createAppointmentForUser(Auth::id(), date('Y-m-d'));
        $app3 = $this->createAppointmentForUser(Auth::id(), date("Y-m-d", strtotime("-5 days")));

        $response = $this->json('GET','/api/user/'.Auth::id().'/appointments/history');
        $response->assertJson([$app3->toArray()]);
    }

    /** @test */
    public function store_creates_appointment_if_requested_time_available() {
        $this->mockAppointmentMail();

        $appointment = $this->makeAppointmentForUser(1);
        $this->post('/api/appointments', $appointment->toArray())->assertCreated();
    }

    /** @test */
    public function store_sends_email_if_appointment_successful() {
        $this->mockAppointmentMail();

        $appointment = $this->makeAppointmentForUser(1);
        $this->post('/api/appointments', $appointment->toArray());

        Mail::assertSent(AppointmentApproved::class);
    }

    /** @test */
    public function store_fails_if_requested_time_unavailable() {
        $this->mockAppointmentMail(false);

        $appointment = $this->makeAppointmentForUser(1);
        $this->post('/api/appointments', $appointment->toArray())->assertStatus(400);

        Mail::assertNothingSent();
    }

    /** @test */
    public function store_fails_if_scheduling_for_another_patient() {
        $appointment = $this->makeAppointmentForUser(2);
        $this->post('/api/appointments', $appointment->toArray())->assertStatus(403);
    }

    /** @test */
    public function accept_sets_appointment_accepted_if_token_valid() {
        Event::fake();
        $appointment = $this->createAppointmentForUser(1);
        $this->get('/api/appointments/'. $appointment->id .'/accept/' . $appointment->token)
            ->assertOk();
        $this->assertTrue(Appointment::find($appointment->id)->accepted == true);
    }

    /** @test */
    public function accept_dispatches_event_if_token_valid() {
        Event::fake();
        $appointment = $this->createAppointmentForUser(1);
        $this->get('/api/appointments/'. $appointment->id .'/accept/' . $appointment->token);
        Event::assertDispatched(AppointmentAccepted::class);
    }

    /** @test */
    public function accept_fails_if_token_invalid() {
        Event::fake();
        $appointment = $this->createAppointmentForUser(1);
        $this->get('/api/appointments/'. $appointment->id .'/accept/someInvalidToken')
            ->assertStatus(400);
    }

    /** @test */
    public function decline_sets_appointment_declined_if_token_valid() {
        $appointment = $this->createAppointmentForUser(1);
        $this->get('/api/appointments/'. $appointment->id .'/decline/' . $appointment->token)
            ->assertOk();
        $this->assertTrue(Appointment::find($appointment->id)->accepted == false);
    }

    /** @test */
    public function decline_fails_if_token_invalid() {
        $appointment = $this->createAppointmentForUser(1);
        $this->get('/api/appointments/'. $appointment->id .'/decline/' . 'someInvalidToken')
            ->assertStatus(400);
    }

    /** @test */
    public function destroy_cancels_appointment() {
        Event::fake();
        $appointment = $this->createAppointmentForUser(1, date('Y-m-d', strtotime('+5 days')));
        $this->delete('/api/appointments/' . $appointment->id)->assertStatus(200);
        $this->assertDeleted($appointment);
    }

    /** @test */
    public function destroy_dispatches_cancelled_event() {
        Event::fake();
        $appointment = $this->createAppointmentForUser(1, date('Y-m-d', strtotime('+5 days')));
        $this->delete('/api/appointments/' . $appointment->id);
        Event::assertDispatched(AppointmentCancelled::class);
    }

    /** @test */
    public function destroy_fails_if_cancelling_another_patients_appointment() {
        Event::fake();
        $appointment = $this->createAppointmentForUser(2);
        $this->delete('/api/appointments/' . $appointment->id)->assertStatus(401);
    }

    /** @test */
    public function destroy_fails_if_cancellation_within_24_hours () {
        Event::fake();
        $appointment = $this->createAppointmentForUser(1, date('Y-m-d'));
        $this->delete('/api/appointments/' . $appointment->id)->assertStatus(400);
    }

    // =================================================================================================================
    // AddToAvailability handler
    // =================================================================================================================

    /** @test */
    public function appointment_accepted_handler_calculates_availability() {
        $doctor = factory(Doctor::class)->create();
        $clinic = factory(Clinic::class)->create();
        $doctor->clinics()->attach($clinic, [
            'works_from' => '08:00:00',
            'works_to' => '16:00:00']);
        $appointment = $this->createAppointmentForUser(1, '2019-12-12', '09:00:00');
        $apptype = factory(AppointmentType::class)->create(['duration' => '00:30:00']);
        $appointment->appointment_type()->associate($apptype);

        $event = new AppointmentAccepted($appointment);
        app(AddToAvailability::class)->handle($event);

        $availabilities = Availability::whereDate('date', '2019-12-12')
            ->where('doctor_id',1)
            ->where('clinic_id',1)
            //->havingRaw('\'time\' + \'duration\' AS end_time > '.$end_time)
            ->get();

        $this->assertTrue($availabilities[0]->time == '08:00:00');
        $this->assertTrue($availabilities[0]->duration == '01:00:00');
        $this->assertTrue($availabilities[1]->time == '09:30:00');
        $this->assertTrue($availabilities[1]->duration == '06:30:00');
    }


    // =================================================================================================================
    // Helper functions
    // =================================================================================================================

    private function mockAppointmentMail($isAvailable=true) {
        $this->mock(AppointmentService::class, function ($mock) use ($isAvailable) {
            $mock->shouldReceive('isAvailable')
                ->once()
                ->andReturn($isAvailable);
        });

        Mail::fake();
    }

    private function createAppointmentForUser($userId, $date=null, $time=null) {
        return factory(Appointment::class)->create($this->appointmentAdditionalData($userId, $date, $time));
    }

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
