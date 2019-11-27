<?php

namespace App\Http\Controllers;

use App\Clinic;
use Illuminate\Http\Request;

class ClinicController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
        $this->middleware('admin')->except(['index', 'show']);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clinics = Clinic::get(['id','name','photo','address','city'])->map(function($clinic) {
            return $clinic->append(['appointment_types', 'doctor_ids', 'appointments_from_duration']);
        });
        return $clinics;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
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
     * Display the specified resource.
     *
     * @param  \App\Clinic  $clinic
     * @return \Illuminate\Http\Response
     */
    public function show(Clinic $clinic)
    {
        $clinic['doctors']=$clinic->doctors;
        return $clinic;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Clinic  $clinic
     * @return \Illuminate\Http\Response
     */
    public function edit(Clinic $clinic)
    {
        //
    }

    /**
     * Update the specified resource in storage.
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
     * Remove the specified resource from storage.
     *
     * @param  \App\Clinic  $clinic
     * @return \Illuminate\Http\Response
     */
    public function destroy(Clinic $clinic)
    {
        $clinic->delete();
        return response()->json(['success'=>true], 200);
    }
}
