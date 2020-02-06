<?php


namespace App\Services;


use App\Appointment;
use App\AppointmentType;
use App\Doctor;
use App\Mail\AppointmentApproved;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AppointmentService
{
    public function storeAppointment($validated, $isAvailable) {
        $appointment = null;
        $validated['token'] = Str::random(16);
        if(!$isAvailable){
            DB::rollBack();
            return response('Appointment time not available', 400);
        } else {
            $appointment = Appointment::create($validated);
        }
        if(!is_null($appointment)) {
            $data = [];
            Mail::to(Auth::user()->email)
                ->send(new AppointmentApproved($appointment, $data));
            DB::commit();
            return response('Appointment successfully created', 201);
        }
        else {
            DB::rollBack();
            return response('Appointment creation failed', 400);
        }
    }
    public function isAvailable($validated) {
        $doctor = Doctor::find($validated['doctor_id']);
        $clinic = $doctor->clinics->find($validated['clinic_id']);
        $work_start = strtotime($clinic->pivot->works_from, 0);
        $work_end = strtotime($clinic->pivot->works_to,0);

        $appointment_type = AppointmentType::find($validated['appointment_type_id']);
        $start_time = strtotime($validated['time'], 0);
        $end_time = $start_time + strtotime($appointment_type->duration, 0);

        if($start_time<$work_start || $end_time>$work_end){
            return false;
        }
        if($doctor->appointments->where('date',$validated['date'])){
            foreach ($doctor->appointments->where('date',$validated['date']) as $appointment) {
                if(!($end_time <= strtotime($appointment->time,0)
                    || $start_time >= strtotime($appointment->appointment_type->duration,0)
                    +strtotime($appointment->time,0))){
                    return false;
                }
            }
        }
        return true;
    }
}
