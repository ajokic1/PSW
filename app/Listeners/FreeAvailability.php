<?php

namespace App\Listeners;

use App\Events\AppointmentCancelled;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class FreeAvailability
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
     * @param  AppointmentCancelled  $event
     * @return void
     */
    public function handle(AppointmentCancelled $event)
    {
        //
    }
}
