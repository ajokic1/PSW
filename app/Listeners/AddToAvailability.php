<?php

namespace App\Listeners;

use App\Availability;
use App\Events\AppointmentAccepted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AddToAvailability
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  AppointmentAccepted  $event
     * @return void
     */
    public function handle(AppointmentAccepted $event)
    {
        $start_time = $event->appointment->time;
        $end_time = date('H:i:s',strtotime($start_time,0)+strtotime($event->appointment->appointment_type->duration,0));
        $availability = Availability::whereTime('time','<',$start_time)
            ->whereTime('time + duration','>',$end_time)
            ->whereDate('date', $event->appointment->date)
            ->where('doctor_id',$event->appointment->doctor_id)
            ->where('clinic_id',$event->appointment->clinic_id)
            ->first();

        if(!$availability){
            $clinic_doctor = App\Clinic::find($event->appointment->clinic_id)
                ->doctors()->where('doctors.id',$event->appointment->doctor_id)
                ->first()->pivot;
            $work_start = strtotime($clinic_doctor->works_from,0);
            $work_duration = strtotime($clinic_doctor->works_to,0)-$work_start;

            $availability = new Availability();
            $availability->doctor_id = $event->appointment->doctor_id;
            $availability->clinic_id = $event->appointment->clinic_id;
            $availability->date = $event->appointment->date;
            $availability->time = date('H:i:s',$work_start);
            $availability->duration = date('H:i:s',$work_duration);
            $availability->save();
        }    

        $availability1 = $availability->replicate();
        $availability1->duration = 
            date('H:i:s', strtotime($start_time,0)-strtotime($availability1->time,0));
        $availability2 = $availability->replicate();
        $availability2->time = $end_time;
        $availability2->duration =
            date('H:i:s', strtotime($availability->time,0)
                +strtotime($availability->duration,0)
                -strtotime($end_time,0));
        $availability->delete();
        $availability1->save();
        $availability2->save();

    }
}
