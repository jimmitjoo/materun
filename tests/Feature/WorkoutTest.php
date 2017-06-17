<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class WorkoutTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function a_user_can_view_workouts()
    {

    }

    /** @test */
    public function a_user_can_create_workouts()
    {
        $data = [
            'tempo' => 240,
            'distance' => 10,
            'latitude' => 56.6616019,
            'longitude' => 16.3443119
        ];


        $response = $this->post('/api/workout', $data);

        $response->assertStatus(200);
    }
}

