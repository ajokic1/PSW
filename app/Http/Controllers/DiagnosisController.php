<?php

namespace App\Http\Controllers;

use App\Diagnosis;
use Cassandra\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class DiagnosisController extends Controller
{
    /**
     * Get a list of all the logged in patient's diagnoses.
     *
     * @return Response
     */
    public function index()
    {
        $user = Auth::user();
        $diagnoses = $user->diagnoses()->with(['condition', 'doctor'])
            ->get();
        return $diagnoses;

    }

    /**
     * Get the specified diagnosis.
     *
     * @param \App\Diagnosis $diagnosis
     * @return Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function show(Diagnosis $diagnosis)
    {
        return $diagnosis->with(['condition', 'doctor'])->get();
    }
}
