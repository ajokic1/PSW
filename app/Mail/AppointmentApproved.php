<?php

namespace App\Mail;

use App\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AppointmentApproved extends Mailable
{
    use Queueable, SerializesModels;

    public $appointment;
    public $data;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Appointment $appointment, $data)
    {
        $this->appointment = $appointment;
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.appointment_approved');
    }
}
