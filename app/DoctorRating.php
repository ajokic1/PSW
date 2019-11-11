<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DoctorRating extends Model
{
    protected $guarded=[];

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function doctor() {
        return $this->belongsTo('App\Doctor');
    }
}
