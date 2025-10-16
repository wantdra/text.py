<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\UserWord;
use App\Models\Word;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserWordFactory extends Factory
{
    protected $model = UserWord::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'word_id' => Word::factory(),
            'ease' => 2.3,
            'streak' => 0,
            'last_result' => null,
            'interval_minutes' => 0,
            'due_at' => now(),
            'review_count' => 0,
            'leech_count' => 0,
        ];
    }
}
