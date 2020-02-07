<?php

namespace App\Http\Controllers;

use App\ClinicRating;
use App\Clinic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClinicRatingController extends Controller
{
    /**
     * Rate a visited clinic.
     *
     * @param  \App\Clinic $clinic
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function rate(Clinic $clinic, Request $request)
    {
        if(is_int($request->rating) and $request->rating>0 and $request->rating<6)
        {
            $user = Auth::user();

            $user->loadCount(['appointments' => function ($query) use ($clinic) {
                $query->where('clinic_id', $clinic->id);
            }]);

            if($user->appointments_count>0) {
                $clinic_rating = ClinicRating::firstOrNew([
                    'user_id' => Auth::id(),
                    'clinic_id' => $clinic->id
                ]);
                $clinic_rating->user_id = $user->id;
                $clinic_rating->clinic_id = $clinic->id;
                $clinic_rating->rating = $request->rating;
                $clinic_rating->comment = $request->comment;
                $clinic_rating->save();
                return response($clinic->rating, 200);

            } else return response('User can only rate visited clinics', 403);
        }
        else return response('Rating must be an integer with a value of 1-5.', 400);
    }

    /**
     * Get a clinic's average rating.
     *
     * @param  \App\Clinic $clinic
     * @return \Illuminate\Http\Response
     */
    public function rating(Clinic $clinic)
    {
        return $clinic->rating;
    }
}
