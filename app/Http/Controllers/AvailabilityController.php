<?php

namespace App\Http\Controllers;

use App\Availability;
use App\AppointmentType;
use Illuminate\Http\Request;

class AvailabilityController extends Controller
{
    public function get(AppointmentType $appointment_type, $date) {
        return Availability::whereDate('date',$date)
            ->whereTime('duration', '>', $appointment_type->duration)
            ->get(['doctor_id','clinic_id','date','time','duration']);
    }
}
