<?php

namespace Database\Factories;

use App\Models\ReviewLog;
use App\Models\User;
use App\Models\Word;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewLogFactory extends Factory
{
    protected $model = ReviewLog::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'word_id' => Word::factory(),
            'result' => $this->faker->randomElement(['hard', 'medium', 'easy']),
            'reviewed_at' => now(),
            'prev_interval' => $this->faker->numberBetween(1, 1000),
            'next_interval' => $this->faker->numberBetween(1, 1000),
        ];
    }
}
