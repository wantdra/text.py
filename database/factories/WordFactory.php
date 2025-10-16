<?php

namespace Database\Factories;

use App\Models\Word;
use Illuminate\Database\Eloquent\Factories\Factory;

class WordFactory extends Factory
{
    protected $model = Word::class;

    public function definition(): array
    {
        $gender = $this->faker->randomElement(['m', 'f', 'n']);

        return [
            'lemma' => $this->faker->unique()->word(),
            'gender' => $gender,
            'plural' => $this->faker->word().'e',
            'level' => $this->faker->randomElement(['A1', 'A2', 'B1']),
            'example_sentence' => ucfirst($this->faker->sentence()).'.',
            'example_translation' => ucfirst($this->faker->sentence()),
            'tags' => [$gender === 'm' ? 'maskulin' : ($gender === 'f' ? 'feminin' : 'neutral')],
        ];
    }
}
