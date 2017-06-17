<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Workout extends Model
{
    public function users(){
        return $this->hasMany('App\User');
    }
}
