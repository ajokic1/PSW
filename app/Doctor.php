<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Doctor extends Model
{
    protected $guarded=[];
    protected $hidden=['created_at', 'updated_at'];
    protected $appends=['rating', 'appointment_types'];
    

    public function appointment_types() {
        return $this->belongsToMany('App\AppointmentType');
    }

    public function clinics() {
        return $this
            ->belongsToMany('App\Clinic')
            ->withPivot(['works_from', 'works_to']);
    }

    public function ratings() {
        return $this->hasMany('App\Rating');
    }

    public function predef_appointments() {
        return $this->hasMany('App\PredefAppointment');
    }

    public function appointments() {
        return $this->hasMany('App\Appointment');
    }
    public function getAppointmentTypesAttribute() {
        $appointment_types = DB::table('appointment_type_doctor')
            ->where('doctor_id', $this->id)
            ->distinct()
            ->pluck('appointment_type_id')
            ->toArray();
        return $appointment_types;
    }
    public function getRatingAttribute() {
        /*$avg_rating=0;
        $ratings = $this->ratings;
        $sum_ratings = $ratings->reduce(function($carry, $item){
            return $carry + $item->rating;
        });
        return $sum_ratings / $ratings->count();*/
        return 4;
    }
}
