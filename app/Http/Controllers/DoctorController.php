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
     * Calculate the availability for the specified doctor, in the specified clinic,
     * for the specified date and duration
     *
     * @param Clinic $clinic
     * @param Doctor $doctor
     * @param $date
     * @param $duration
     * @return array
     */
    public function availability(Clinic $clinic, Doctor $doctor, $date, $duration) {
        $date = strtotime($date, 0);
        $duration = strtotime($date, 0);
        $work_start = strtotime($clinic->doctors()
            ->where('doctors.id', $doctor->id)->first()->pivot->works_from,0);
        $work_end = strtotime($clinic->doctors()
            ->where('doctors.id', $doctor->id)->first()->pivot->works_to,0);

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
