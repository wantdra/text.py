<?php

namespace App\Http\Livewire;

use App\Models\UserWord;
use App\Models\Word;
use App\Services\SrsService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class LearnCard extends Component
{
    public array $card = [];
    public string $status = '';

    public function mount(): void
    {
        $this->loadCard();
    }

    public function loadCard(): void
    {
        $user = Auth::user();
        $now = Carbon::now();

        $due = UserWord::with('word')
            ->where('user_id', $user->id)
            ->whereNotNull('due_at')
            ->where('due_at', '<=', $now)
            ->orderBy('due_at')
            ->first();

        if (! $due) {
            $studiedIds = UserWord::where('user_id', $user->id)->pluck('word_id');
            $word = Word::whereNotIn('id', $studiedIds)->inRandomOrder()->first()
                ?? Word::inRandomOrder()->first();
        } else {
            $word = $due->word;
        }

        if ($word) {
            $userWord = UserWord::firstOrCreate([
                'user_id' => $user->id,
                'word_id' => $word->id,
            ]);

            $this->card = [
                'id' => $word->id,
                'lemma' => $word->lemma,
                'gender' => $word->gender,
                'plural' => $word->plural,
                'example_sentence' => $word->example_sentence,
                'example_translation' => $word->example_translation,
                'due_at' => optional($userWord->due_at)->format('d.m.Y H:i'),
            ];
            $this->status = $this->card['due_at'] ? 'Sonraki tekrar: '.$this->card['due_at'] : '';
        } else {
            $this->card = [];
            $this->status = 'Gösterilecek kelime yok.';
        }
    }

    public function answer(string $result, SrsService $srs): void
    {
        if (! isset($this->card['id'])) {
            return;
        }

        $user = Auth::user();
        $word = Word::find($this->card['id']);

        if (! $word) {
            return;
        }

        $response = $srs->review($user, $word, $result);
        $this->status = 'Sonraki tekrar: '.$response['dueDate']->format('d.m.Y H:i');
        $this->loadCard();
    }

    public function render()
    {
        return view('livewire.learn-card');
    }
}
