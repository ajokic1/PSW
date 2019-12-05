<?php

namespace App\Http\Controllers;

use App\Appointment;
use \Exception;
use App\Doctor;
use App\Clinic;
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
    public function index()
    {

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
                'user_id'=>'required',
                'doctor_id'=>'required',
                'clinic_id'=>'required',
                'appointment_type_id'=>'required',
                'date'=>'required',
                'time'=>'required',
            ]);
            $validated['token'] = Str::random(16);
            if(Auth::id()!=$validated['user_id']){
                throw new Exception('Appointment user_id doesn\'t match id of logged in user');
            } else if(!$this->isAvailable($validated)){
                throw new Exception('The selected appointment time is not available');
            } else {
                $appointment = Appointment::create($validated);
            }
        //});
        if($appointment) {
            $data = [];
            Mail::to(Auth::user()->email)
                ->send(new AppointmentApproved($appointment, $data));
            return response('Appointment successfully created', 201);
        }
        else return response('Appointment creation failed', 400);
        
    }

    private function isAvailable($validated) {
        $doctor = Doctor::find($validated['doctor_id']);
        $clinic = $doctor->clinics->find($validated['clinic_id']);
        $work_start = $clinic->pivot->works_from;
        $work_end = $clinic->pivot->works_to;

        $appointment_type = AppointmentType::find($validated['appointment_type_id']);
        $start_time = strtotime($validated['time'], 0);
        $end_time = $start_time + strtotime($appointment_type->duration, 0); 
        
        if($start_time<$work_start || $end_time>$work_end){
            return false;
        }

        foreach ($doctor->appointments as $appointment) {
            if(!($end_time < strtotime($appointment->time,0) 
                    || $start_time > strtotime($appointment->appointment_type->duration,0)
                    +strtotime($appointment->time,0))){
                return false;
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
        //
    }

    public function details(Doctor $doctor, Clinic $clinic, AppointmentType $appointment_type, $date) {
        $data['doctor'] = $doctor;
        $data['clinic'] = $clinic;
        $data['appointment_type'] = $appointment_type;
        $data['availability'] = Availability::whereDate('date',$date)
            ->where('doctor_id',$doctor->id)
            ->where('clinic_id',$clinic->id)
            ->get(['id','time','duration']);
        return $data;
    }
}
