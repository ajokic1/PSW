<?php

namespace App\Http\Controllers;

use App\Doctor;
use App\Clinic;
use App\Appointment;
use App\AppointmentType;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Doctor  $doctor
     * @return \Illuminate\Http\Response
     */
    public function show(Doctor $doctor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Doctor  $doctor
     * @return \Illuminate\Http\Response
     */
    public function edit(Doctor $doctor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Doctor  $doctor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Doctor $doctor)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Doctor  $doctor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Doctor $doctor)
    {
        //
    }

    public function availability(Clinic $clinic, Doctor $doctor, $date, $duration) {
        $date = strtotime($date, 0);
        $duration = strtotime($date, 0);
        $work_start = strtotime($clinic->doctors()
            ->where('doctor_id', $doctor->id)->first()->pivot->works_from,0);
        $work_end = strtotime($clinic->doctors()
            ->where('doctor_id', $doctor->id)->first()->pivot->works_to,0);

        $appointments = $clinic->appointments()
            ->where('doctor_id', $doctor->id)->get()->sortBy('time');

        $curr = $work_start;
        $available_intervals = [];
        foreach($appointments as $appointment) {
            $appointment_time = strtotime($appointment->time, 0);
            $appointment_duration = strtotime($appointment->appointment_type->duration, 0);
            
            if($appointment_time-$curr > $duration) 
                array_push($available_intervals, [$curr, $appointment_time]);

            $curr=$appointment_time + $appointment_duration;
        }
        if($work_end-$curr > $duration)
            array_push($available_intervals, [$curr, $work_end]);

        return $available_intervals;
    }
}
