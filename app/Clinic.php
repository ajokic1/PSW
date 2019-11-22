<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Clinic extends Model
{
    protected $guarded=[];

    protected $appends = ['appointment_types', 'doctor_ids', 'appointments_from_duration'];

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
        return $this->doctors()->get(['doctor_id','works_from', 'works_to'])->makeHidden('pivot')->toArray();
    }
    
    public function getAppointmentTypesAttribute() {
        $appointment_types = DB::table('appointment_type_doctor')
            ->whereIn('doctor_id', $this->doctor_ids)
            ->distinct()
            ->pluck('appointment_type_id')
            ->toArray();
        return $appointment_types;
    }
    public function getAppointmentsFromDurationAttribute() {
        return DB::table('clinics')
            ->where('clinics.id', '=', $this->id)
            ->join('appointments', 'clinics.id', '=', 'appointments.clinic_id')
            ->join('appointment_types', 'appointments.appointment_type_id', '=', 'appointment_types.id')
            ->select('appointments.id', 'appointments.time', 'appointment_types.duration')
            ->get();
    }
}
