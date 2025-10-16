<?php

namespace App\Http\Livewire\Games;

use App\Models\UserWord;
use App\Models\Word;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class DragDrop extends Component
{
    public array $columns = [
        'der' => [],
        'die' => [],
        'das' => [],
    ];

    public array $items = [];
    public int $score = 0;
    public bool $finished = false;

    public function mount(): void
    {
        $this->items = $this->loadPool();
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
            return $words->merge($rest)->shuffle()->values()->map(fn ($word) => [
                'id' => $word->id,
                'lemma' => $word->lemma,
                'gender' => $word->gender,
            ])->all();
        }

        return $query->inRandomOrder()->limit(15)->get()->map(fn ($word) => [
            'id' => $word->id,
            'lemma' => $word->lemma,
            'gender' => $word->gender,
        ])->all();
    }

    public function drop(string $artikel, int $wordId): void
    {
        if ($this->finished) {
            return;
        }

        foreach ($this->items as $key => $item) {
            if ($item['id'] === $wordId) {
                unset($this->items[$key]);
                $this->columns[$artikel][] = $item;
                if ($this->mapGender($artikel) === $item['gender']) {
                    $this->score += 1;
                }
                break;
            }
        }

        if (empty($this->items)) {
            $this->finished = true;
        }
    }

    protected function mapGender(string $artikel): string
    {
        return match ($artikel) {
            'der' => 'm',
            'die' => 'f',
            default => 'n',
        };
    }

    public function render()
    {
        return view('livewire.games.drag-drop');
    }
}
