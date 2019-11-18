<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    protected $guarded=[];

    public function appointment_types() {
        return $this->belongsToMany('App\AppointmentType');
    }

    public function clinics() {
        return $this
            ->belongsToMany('App\Clinic')
            ->using('App\ClinicDoctor')
            ->withPivot(['works_from', 'works_to']);
    }

    public function ratings() {
        return $this->hasMany('App\Rating');
    }

    public function predef_appointments() {
        return $this->hasMany('App\PredefAppointment');
    }
}
