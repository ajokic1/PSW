<?php

namespace App\Http\Controllers;

use App\Appointment;
use App\Events\AppointmentAccepted;
use App\Events\AppointmentCancelled;
use App\Http\Requests\StoreAppointmentRequest;
use App\Services\AppointmentService;
use \Exception;
use App\Doctor;
use App\Clinic;
use App\User;
use App\AppointmentType;
use App\Availability;
use App\Mail\AppointmentApproved;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AppointmentController extends Controller
{
    private $appointmentService;

    public function __construct(AppointmentService $appointmentService)
    {
        $this->appointmentService = $appointmentService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(User $user)
    {
        if($user->id != Auth::id())
            return response('A patient can only access their own appointments', 401);
        return Appointment::where('user_id',$user->id)
            ->whereDate('date', '>=', date("Y-m-d"))
            ->with(['clinic','doctor','appointment_type'])
            ->get();
    }
    public function indexHistory(User $user)
    {
        if($user->id != Auth::id())
            return response('A patient can only access their own appointments', 401);
        return Appointment::where('user_id',$user->id)
            ->whereDate('date', '<', date("Y-m-d"))
            ->with(['clinic','doctor','appointment_type'])
            ->get();
    }



    /**
     * Create a new appointment.
     *
     * @param StoreAppointmentRequest $request
     * @return Response
     * @throws Exception
     */
    public function store(StoreAppointmentRequest $request)
    {
        DB::beginTransaction();
        $validated = $request->validated();
        $isAvailable = $this->appointmentService->isAvailable($validated);
        return $this->appointmentService->storeAppointment($validated, $isAvailable);
    }

    /**
     * Accept an appointment.
     *
     * @param Appointment $appointment
     * @param $token
     * @return ResponseFactory|Response
     */
    public function accept(Appointment $appointment, $token) {
        if($appointment->token == $token) {
            $appointment->accepted = true;
            event(new AppointmentAccepted($appointment));
            $appointment->save();
            return response('Appointment accepted',200);
        } else return response('Invalid token', 400);
    }

    /**
     * Decline an appointment.
     *
     * @param Appointment $appointment
     * @param $token
     * @return ResponseFactory|Response
     */
    public function decline(Appointment $appointment, $token) {
        if($appointment->token == $token){
            $appointment->accepted = false;
            $appointment->save();
            return response('Appointment declined',200);
        }
        return response('Invalid token', 400);
    }

    /**
     * Cancel an appointment.
     *
     * @param \App\Appointment $appointment
     * @return Response
     * @throws Exception
     */
    public function destroy(Appointment $appointment)
    {
        if($appointment->user_id != Auth::id())
            return response('Patients can only cancel their own appointments', 401);
        $timestamp = strtotime($appointment->date . ' ' . $appointment->time);
        $curr_timestamp = time();
        if($timestamp - $curr_timestamp < strtotime('24:00:00',0))
            return response('Appointments can be cancelled until 24 hours before
             due time', 400);
        event(new AppointmentCancelled($appointment));
        $appointment->delete();

        return response('Appointment cancelled', 200);

    }

    public function details(Doctor $doctor, Clinic $clinic, AppointmentType $appointment_type, $date) {
        $data['doctor'] = $doctor;
        $data['clinic'] = $clinic;
        $data['appointment_type'] = $appointment_type;
        $availabilities = Availability::whereDate('date',$date)
            ->where('doctor_id',$doctor->id)
            ->where('clinic_id',$clinic->id)
            ->get(['id','time','duration']);
        if($availabilities->isEmpty()){
            $availabilities = [['id'=>1, 'time'=>'08:00:00', 'duration'=>'08:00:00']];
        }
        $available_times = array();
        $id=0;
        foreach ($availabilities as $a) {
            $start = strtotime($a['time'],0);
            $end = $start + strtotime($a['duration']) - strtotime($appointment_type['duration']);
            $curr = $start;
            $step = 30 * 60;
            while($curr<$end){
                $time=['id'=>$id, 'time'=>date('H:i:s', $curr), 'duration'=>$appointment_type->duration];
                array_push($available_times, $time);
                $id++;
                $curr = $curr + $step;
            }
        }
        $data['availability'] = $available_times;
        return $data;
    }
}
