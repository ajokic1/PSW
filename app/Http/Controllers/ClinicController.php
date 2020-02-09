<?php

namespace App\Http\Controllers;

use App\Clinic;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ClinicController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
        $this->middleware('admin')->except(['index', 'show']);
    }

    /**
     * Get a list of all clinics.
     *
     * @return \Illuminate\Http\Response $clinics
     */
    public function index()
    {
        $clinics = Clinic::get(['id','name','photo','address','city'])->map(function($clinic) {
            return $clinic->append(['appointment_types', 'doctor_ids', 'appointments_from_duration']);
        });
        return $clinics;
    }

    /**
     * Create a new clinic.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'=>'required',
            'description'=>'nullable',
            'photo'=>'nullable',
            'address'=>'required',
            'city'=>'required',
            'country'=>'required',
        ]);
        Clinic::create($validated);
        return response()->json(['success'=>true], 201);
    }

    /**
     * Get the specified clinic.
     *
     * @param  \App\Clinic  $clinic
     * @return \App\Clinic
     */
    public function show(Clinic $clinic)
    {
        $canRate = false;
        $user = Auth::id();
        $clinic->loadCount(['appointments' => function ($query) use ($user) {
                $query->where('user_id', $user);
            }]);
        if($clinic->appointments_count>0) {
            $canRate=true;
        }

        $clinic['canRate']=$canRate;
        $clinic['doctors']=$clinic->doctors;
        return $clinic;
    }
    /**
     * Update the specified clinic.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Clinic  $clinic
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Clinic $clinic)
    {
        $validated = $request->validate([
            'name'=>'required',
            'description'=>'nullable',
            'photo'=>'nullable',
            'address'=>'required',
            'city'=>'required',
            'country'=>'required',
        ]);
        $clinic->update($validated);
        return response()->json(['success'=>true], 200);
    }

    /**
     * Delete the specified clinic.
     *
     * @param \App\Clinic $clinic
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Clinic $clinic)
    {
        $clinic->delete();
        return response()->json(['success'=>true], 200);
    }
}
