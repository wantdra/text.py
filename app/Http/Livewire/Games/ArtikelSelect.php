<?php

namespace App\Http\Livewire\Games;

use App\Models\UserWord;
use App\Models\Word;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ArtikelSelect extends Component
{
    public array $pool = [];
    public int $index = 0;
    public int $score = 0;
    public bool $finished = false;
    public ?string $feedback = null;

    public function mount(): void
    {
        $this->pool = $this->loadPool();
    }

    protected function loadPool(): array
    {
        $user = Auth::user();
        $leechIds = UserWord::where('user_id', $user->id)
            ->where('leech_count', '>=', 5)
            ->pluck('word_id')
            ->toArray();

        $query = Word::query()->forGamePool('artikel');
        if (! empty($leechIds)) {
            $words = Word::whereIn('id', $leechIds)->limit(10)->get();
            $rest = $query->whereNotIn('id', $leechIds)->limit(10)->get();
            return $words->merge($rest)->shuffle()->values()->map(fn ($word) => $word->toArray())->all();
        }

        return $query->inRandomOrder()->limit(20)->get()->map(fn ($word) => $word->toArray())->all();
    }

    public function answer(string $gender): void
    {
        if ($this->finished || ! isset($this->pool[$this->index])) {
            return;
        }

        $current = $this->pool[$this->index];
        if ($current['gender'] === $gender) {
            $this->score += 1;
            $this->feedback = 'Doğru!';
        } else {
            $this->feedback = 'Yanlış. Doğru artikel: '.match ($current['gender']) {
                'm' => 'der',
                'f' => 'die',
                default => 'das',
            };
        }

        $this->index += 1;
        if ($this->index >= count($this->pool)) {
            $this->finished = true;
        }
    }

    public function render()
    {
        return view('livewire.games.artikel-select');
    }
}
