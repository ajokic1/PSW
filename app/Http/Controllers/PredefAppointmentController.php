<?php

namespace App\Http\Controllers;

use App\Clinic;
use App\Http\Requests\StoreAppointmentRequest;
use App\PredefAppointment;
use App\Services\AppointmentService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PredefAppointmentController extends Controller
{
    private $appointmentService;

    public function __construct(AppointmentService $appointmentService) {
        $this->middleware('auth');
        $this->middleware('admin')->except(['index', 'choose']);
        $this->appointmentService = $appointmentService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Clinic $clinic)
    {
        return $clinic->predef_appointments;
    }

    public function choose(PredefAppointment $predef_appointment)
    {
        $validated = [
            'user_id' => Auth::id(),
            'doctor_id' => $predef_appointment->doctor_id,
            'clinic_id' => $predef_appointment->clinic_id,
            'appointment_type_id' => $predef_appointment->appointment_type_id,
            'date' => $predef_appointment->date,
            'time' => $predef_appointment->time,
        ];
        DB::beginTransaction();
        $isAvailable = $this->appointmentService->isAvailable($validated);
        $response = $this->appointmentService->storeAppointment($validated, $isAvailable);
        if($response->getStatusCode() == 201) {
            try {
                $predef_appointment->delete();
            } catch (\Exception $e) {
                return response('Couldn\'t delete predef.', 500);
            }
        }
        return $response;
    }


}
