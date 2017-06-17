<?php

namespace App\Http\Controllers;

use App\Workout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WorkoutController extends Controller
{
    //

    public function store(Request $request)
    {

        $this->validate($request, [
            'tempo' => 'required|integer',
            'distance' => 'required|integer',
            'latitude' => 'required',
            'longitude' => 'required',
            'starting' => 'required'
        ]);

        $workout = new Workout();
        $workout->tempo = $request->get('tempo');
        $workout->distance = $request->get('distance');
        $workout->latitude = $request->get('latitude');
        $workout->longitude = $request->get('longitude');
        $workout->starting = $request->get('starting');
        $workout->save();

        return $workout;

    }

    public function getWorkoutsByCoordinates($latitude, $longitude)
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
             WHERE distance_in_km < .25 AND DATE(stdate) >= DATE(NOW()) AND DATE(stdate) = CURDATE() 
             ORDER BY distance_in_km ASC
             LIMIT 15');

        foreach ($nearbys as $n) {
            $n->starts_at = date('H:i', strtotime($n->stdate));
        }

        return $nearbys;

    }
}
