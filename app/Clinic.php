<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Clinic extends Model
{
    protected $guarded=[];

    protected $appends = ['appointment_types', 'doctor_ids'];

    public function clinic_ratings() {
        return $this->hasMany('App\ClinicRating');
    }

    public function doctors() {
        return $this->belongsToMany('App\Doctor')->withPivot(['works_from', 'works_to']);
    }

    public function appointments() {
        return $this->hasMany('App\Appointment');
    }

    public function predef_appointments() {
        return $this->hasMany('App\PredefAppointment');
    }

    public function getDoctorIdsAttribute() {
        return $this->doctors()->pluck('doctor_id')->toArray();
    }
    
    public function getAppointmentTypesAttribute() {
        $appointment_types = DB::table('appointment_type_doctor')
            ->whereIn('doctor_id', $this->doctor_ids)
            ->distinct()
            ->pluck('appointment_type_id')
            ->toArray();
        return $appointment_types;

    }
}
