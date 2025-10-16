<?php

namespace App\Http\Livewire\Games;

use App\Models\UserWord;
use App\Models\Word;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ClozeQuiz extends Component
{
    public array $pool = [];
    public int $index = 0;
    public int $score = 0;
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

        $query = Word::query()->forGamePool('cloze');
        if (! empty($leechIds)) {
            $words = Word::whereIn('id', $leechIds)->limit(10)->get();
            $rest = $query->whereNotIn('id', $leechIds)->limit(10)->get();
            return $words->merge($rest)->shuffle()->values()->map(fn ($word) => $word->toArray())->all();
        }

        return $query->inRandomOrder()->limit(20)->get()->map(fn ($word) => $word->toArray())->all();
    }

    public function answer(string $artikel): void
    {
        if ($this->finished || ! isset($this->pool[$this->index])) {
            return;
        }

        $current = $this->pool[$this->index];
        $correct = match ($current['gender']) {
            'm' => 'der',
            'f' => 'die',
            default => 'das',
        };

        if ($correct === $artikel) {
            $this->score += 1;
            $this->feedback = 'Doğru!';
        } else {
            $this->feedback = 'Yanlış. Cümlede olması gereken: '.$correct;
        }

        $this->index += 1;
        if ($this->index >= count($this->pool)) {
            $this->finished = true;
        }
    }

    public function render()
    {
        return view('livewire.games.cloze-quiz');
    }
}
