<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AppointmentType extends Model
{
    protected $guarded=[];
    protected $hidden=['created_at', 'updated_at'];

    public function appointments() {
        return $this->hasMany('App\Appointment');
    }

    public function doctors() {
        return $this->belongsToMany('App\Doctor');
    }

    public function predef_appointments() {
        return $this->hasMany('App\PredefAppointment');
    }
}
