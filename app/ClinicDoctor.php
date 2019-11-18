<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ClinicDoctor extends Pivot
{
    public function clinic() {
        return $this->belongsTo('App\Clinic');
    }

    public function doctor() {
        return $this->belongsTo('App\Doctor');
    }

    public function appointment_types() {
        return $this->hasManyThrough('App\AppointmentType', 'App\Doctor');
    }
}
