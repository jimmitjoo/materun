<?php

namespace App\Providers;

use App\User;
use App\Workout;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        Validator::extend('unrealistic_tempo', function ($attribute, $value, $parameters, $validator) {
            return $value > 149;
        });

        Validator::extend('not_attending_workout', function ($attribute, $value, $parameters, $validator) {

            $user_id = $parameters[0];

            $plus2hours = date('Y-m-d H:i:s', strtotime($value . ' + 2 hours'));
            $minus2hours = date('Y-m-d H:i:s', strtotime($value . ' - 2 hours'));

            $otherWorkouts = Workout::where('user_id', $user_id)->where('starting', '<=', $plus2hours)->where('starting', '>=', $minus2hours)->get();

            $attendingWorkouts = User::find($user_id)->workouts()->where('starting', '<=', $plus2hours)->where('starting', '>=', $minus2hours)->get();

            return count($otherWorkouts) === 0 && count($attendingWorkouts) === 0;
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
