<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Clinic extends Model
{
    protected $guarded=[];

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

    public function appointment_types() {
        return $this->hasManyThrough(
            'App\AppointmentType',
            'App\ClinicDoctor',
            'clinic_id',
            'doctor_id',
            'id',
            'doctor_id'
        );
    }
}
