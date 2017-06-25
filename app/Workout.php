<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Workout extends Model
{
    public function owner(){
        return $this->hasOne('App\User');
    }

    public function attendees(){
        return $this->belongsToMany('App\User', 'workout_user');
    }
}
