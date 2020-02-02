<?php

namespace App\Http\Controllers;

use App\DoctorRating;
use App\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoctorRatingController extends Controller
{
    /**
     * Rate a visited doctor.
     *
     * @param  \App\Doctor $doctor
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function rate(Doctor $doctor, Request $request)
    {
        if(is_int($request->rating) and $request->rating>0 and $request->rating<6)
        { 
            $user = Auth::user();

            $user->loadCount(['appointments' => function ($query) use ($clinic) {
                $query->where('doctor_id', $doctor->id);
            }]);

            if($user->appointments_count>0) {
                $doctor_rating = $user->doctor_ratings()
                    ->where('doctor_id', $doctor->id)->firstOrNew();
                $doctor_rating->user_id = $user->id;
                $doctor_rating->doctor_id = $doctor->id;
                $doctor_rating->rating = $request->rating;
                $doctor_rating->comment = $request->comment;
                $doctor_rating->save();

            } else return response('User can only rate visited doctors', 403);
        }
        else return response('Rating must be an integer with a value of 1-5.', 400);
    }

    /**
     * Get a doctor's average rating.
     *
     * @param  \App\Doctor $doctor
     * @return \Illuminate\Http\Response
     */
    public function rating(Doctor $doctor)
    {  
        return $doctor->rating;
    }
}
