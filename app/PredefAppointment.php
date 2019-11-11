<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PredefAppointment extends Model
{
    protected $guarded=[];

    public function doctor() {
        return $this->belongsTo('App\Doctor');
    }

    public function clinic() {
        return $this->belongsTo('App\Clinic');
    }

    public function doctor() {
        return $this->belongsTo('App\AppointmentType');
    }
}
