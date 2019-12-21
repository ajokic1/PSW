<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Diagnosis extends Model
{
    protected $hidden=['created_at','updated_at'];
    protected $appends=['timestamp'];
    protected $guarded=[];

    public function condition() {
        return $this->belongsTo('App\Condition');
    }
    public function doctor() {
        return $this->belongsTo('App\Doctor');
    }
    public function getTimestampAttribute() {
        return strtotime($this->created_at);
    }
}
