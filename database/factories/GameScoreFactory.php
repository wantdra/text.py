<?php

namespace Database\Factories;

use App\Models\GameScore;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class GameScoreFactory extends Factory
{
    protected $model = GameScore::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'game_type' => $this->faker->randomElement(['artikel', 'plural', 'cloze', 'dragdrop']),
            'score' => $this->faker->numberBetween(0, 100),
            'duration_sec' => $this->faker->numberBetween(30, 600),
            'created_at' => now(),
        ];
    }
}
