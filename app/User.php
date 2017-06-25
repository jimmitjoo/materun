<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function workouts(){
        return $this->belongsToMany('App\Workout', 'workout_user');
    }

    public function getMyWorkoutAttribute(){
        if ($workout = Workout::where('user_id', $this->id)->latest()->first()) return $workout->id;

        return 0;
    }
}
