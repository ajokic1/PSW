<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $guarded=[];
    protected $appends = ['time_from_to'];

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function doctor() {
        return $this->belongsTo('App\Doctor');
    }

    public function clinic() {
        return $this->belongsTo('App\Clinic');
    }

    public function appointment_type() {
        return $this->belongsTo('App\AppointmentType');
    }
    
}
