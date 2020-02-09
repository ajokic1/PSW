<?php

namespace App\Http\Controllers;

use App\AppointmentType;
use Illuminate\Http\Request;

class AppointmentTypeController extends Controller
{
    /**
     * Get a list of all appointment types.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return AppointmentType::get(['id','type','name','duration']);
    }

}
