<?php

namespace App\Http\Controllers;

use App\Availability;
use App\AppointmentType;
use Illuminate\Http\Request;

class AvailabilityController extends Controller
{
    /**
     * Get the availabilities for the specified date.
     *
     * @param $date
     * @return mixed
     */
    public function get($date) {
        return Availability::whereDate('date',$date)
            ->get(['doctor_id','clinic_id','date','time','duration']);
    }
}
