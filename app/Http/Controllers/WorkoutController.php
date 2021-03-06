<?php

namespace App\Http\Controllers;

use App\Notifications\JoinedWorkout;
use App\User;
use App\Workout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WorkoutController extends Controller
{
    //

    public function store(Request $request)
    {

        $this->validate($request, [
            'user_id' => 'required|integer',
            'tempo' => 'required|integer|unrealistic_tempo',
            'distance' => 'required|integer',
            'latitude' => 'required',
            'longitude' => 'required',
            'starting' => 'required|not_attending_workout:' . $request->get('user_id'),
        ]);

        $workout = new Workout();
        $workout->user_id = $request->get('user_id');
        $workout->tempo = $request->get('tempo');
        $workout->distance = $request->get('distance');
        $workout->latitude = $request->get('latitude');
        $workout->longitude = $request->get('longitude');
        $workout->starting = $request->get('starting');
        $workout->description = $request->get('description');
        $workout->save();

        return $workout;

    }

    public function show($id){
        $workout = Workout::with('attendees')->find($id);

        $workout->humanTime = date('H:i', strtotime($workout->starting));

        if (date('Y-m-d') == date('Y-m-d', strtotime($workout->starting))) {
            $workout->humanDate = 'Today';
        } elseif (date('Y-m-d', strtotime('+1 day')) == date('Y-m-d', strtotime($workout->starting))) {
            $workout->humanDate = 'Tomorrow';
        } else {
            $workout->humanDate = date('d M');
        }

        return $workout;
    }

    public function destroy($id){

        dd($id);

        $workout = Workout::where('id',$id)->where('user_id', $user_id)->first();
        $workout->delete();
    }

    public function getWorkoutsByCoordinates($latitude, $longitude, $radie = 25)
    {

        $nearbys = DB::select('SELECT * FROM(SELECT id, `starting` as stdate, distance, tempo, latitude, longitude,
                  111.045 * DEGREES(ACOS(COS(RADIANS(latpoint))
                      * COS(RADIANS(latitude))
                      * COS(RADIANS(longpoint) - RADIANS(longitude))
                      + SIN(RADIANS(latpoint))
                      * SIN(RADIANS(latitude)))) AS distance_in_km
             FROM workouts
             JOIN (
                 SELECT ' . $latitude . ' AS latpoint,  ' . $longitude . ' AS longpoint
               ) AS p
             ORDER BY distance_in_km ASC) AS x
             WHERE distance_in_km < ' . $radie . ' AND DATE(stdate) >= DATE(NOW())
             ORDER BY distance_in_km ASC
             LIMIT 15');

        $workouts = [];
        foreach ($nearbys as $n) {
            //$n->starts_at = date('H:i', strtotime($n->stdate));
            $workout = Workout::with('attendees')->find($n->id);
            $workout->starts_at = date('d/m H:i', strtotime($workout->starting));
            $workout->distance_in_km = $n->distance_in_km;
            $workouts[] = $workout;
        }

        return $workouts;

    }

    public function join($workout_id, Request $request){

        $user_id = $request->get('id');

        $workout = Workout::find($workout_id);

        $request->merge(['starting' => $workout->starting]);


        $this->validate($request, [
            'starting' => 'required|not_attending_workout:' . $user_id,
        ]);


        $workout->attendees()->attach($user_id);

        $user = User::find($workout->user_id);

        $user->notify(new JoinedWorkout($workout));

    }

    public function leave(Workout $workout_id, Request $request){

        $user_id = $request->get('id');

        $workout_id->attendees()->detach($user_id);

    }
}
