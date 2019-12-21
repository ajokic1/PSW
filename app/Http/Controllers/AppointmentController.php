<?php

namespace App\Http\Controllers;

use App\Appointment;
use App\Events\AppointmentAccepted;
use App\Events\AppointmentCancelled;
use \Exception;
use App\Doctor;
use App\Clinic;
use App\User;
use App\AppointmentType;
use App\Availability;
use App\Mail\AppointmentApproved;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //DB::transaction(function() {
            $validated = $request->validate([
                'user_id'=>'nullable',
                'doctor_id'=>'required',
                'clinic_id'=>'required',
                'appointment_type_id'=>'required',
                'date'=>'required',
                'time'=>'required',
            ]);
            $validated['token'] = Str::random(16);
            $validated['user_id'] = Auth::id();
            //if(Auth::id()!=$validated['user_id']){
            //    throw new Exception('Appointment user_id doesn\'t match id of logged in user');}
            if(!$this->isAvailable($validated)){
                throw new Exception('The selected appointment time is not available');
            } else {
                $appointment = Appointment::create($validated);
            }
        //});
        if($appointment) {
            $data = [];
            Mail::to(Auth::user()->email)
                ->send(new AppointmentApproved($appointment, $data));
            event(new AppointmentAccepted($appointment));
            return response('Appointment successfully created', 201);
        }
        else return response('Appointment creation failed', 400);
        
    }

    public function isAvailable($validated) {
        $doctor = Doctor::find($validated['doctor_id']);
        $clinic = $doctor->clinics->find($validated['clinic_id']);
        $work_start = strtotime($clinic->pivot->works_from, 0);
        $work_end = strtotime($clinic->pivot->works_to,0);

        $appointment_type = AppointmentType::find($validated['appointment_type_id']);
        $start_time = strtotime($validated['time'], 0);
        $end_time = $start_time + strtotime($appointment_type->duration, 0); 

        if($start_time<$work_start || $end_time>$work_end){            
            return false;
        }
        if($doctor->appointments->where('date',$validated['date'])){
            foreach ($doctor->appointments->where('date',$validated['date']) as $appointment) {
                if(!($end_time <= strtotime($appointment->time,0) 
                        || $start_time >= strtotime($appointment->appointment_type->duration,0)
                        +strtotime($appointment->time,0))){
                    return false;
                }
            }
        }
        return true;
    }

    public function accept(Appointment $appointment, $token) {
        if($appointment->token == $token){
            $appointment->accepted = true;
            event(new AppointmentAccepted($appointment));
            $appointment->save();
            return response('Appointment accepted',200);
        }
        return response('Invalid token', 400);
    }

    public function decline(Appointment $appointment, $token) {
        if($appointment->token == $token){
            $appointment->accepted = false;
            $appointment->save();
            return response('Appointment declined',200);
        }
        return response('Invalid token', 400);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function show(Appointment $appointment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function edit(Appointment $appointment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Appointment $appointment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Appointment  $appointment
     * @return \Illuminate\Http\Response
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
