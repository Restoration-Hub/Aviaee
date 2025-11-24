<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class MissionFactory extends Factory
{
    /**
     * Static counter used to generate sequential mission names (e.g., 1, 2, 3...).
     */
    protected static int $missionCounter = 0;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        static::$missionCounter++;

        $missionNumber = str_pad(static::$missionCounter, 3, '0', STR_PAD_LEFT);

        return [
            'mission_name' => 'MISSION-' . $missionNumber,
            'destination' => fake()->address(),
            'starting_location' => fake()->address(),
        ];
    }
}
