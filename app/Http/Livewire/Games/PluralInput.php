<?php

namespace App\Http\Livewire\Games;

use App\Models\UserWord;
use App\Models\Word;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PluralInput extends Component
{
    public array $pool = [];
    public int $index = 0;
    public int $score = 0;
    public string $answer = '';
    public ?string $feedback = null;
    public bool $finished = false;

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

        $query = Word::query()->forGamePool('plural');
        if (! empty($leechIds)) {
            $words = Word::whereIn('id', $leechIds)->limit(10)->get();
            $rest = $query->whereNotIn('id', $leechIds)->limit(10)->get();
            return $words->merge($rest)->shuffle()->values()->map(fn ($word) => $word->toArray())->all();
        }

        return $query->inRandomOrder()->limit(20)->get()->map(fn ($word) => $word->toArray())->all();
    }

    public function submit(): void
    {
        if ($this->finished || ! isset($this->pool[$this->index])) {
            return;
        }

        $current = $this->pool[$this->index];
        if (mb_strtolower(trim($this->answer)) === mb_strtolower($current['plural'])) {
            $this->score += 1;
            $this->feedback = 'Harika!';
        } else {
            $this->feedback = 'Yanlış. Doğru çoğul: '.$current['plural'];
        }

        $this->index += 1;
        $this->answer = '';

        if ($this->index >= count($this->pool)) {
            $this->finished = true;
        }
    }

    public function render()
    {
        return view('livewire.games.plural-input');
    }
}
