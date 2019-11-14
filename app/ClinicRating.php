<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClinicRating extends Model
{
    protected $guarded=[];

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function clinic() {
        return $this->belongsTo('App\Clinic');
    }
}
