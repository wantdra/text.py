<?php

namespace App\Http\Controllers;

use App\Models\UserWord;
use App\Models\Word;
use App\Services\SrsService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LearnController extends Controller
{
    public function __construct(private readonly SrsService $srs)
    {
    }

    public function index()
    {
        return view('learn.index');
    }

    public function next(): JsonResponse
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

        if (! $word) {
            return response()->json(['message' => 'Hiç kelime bulunamadı.'], 404);
        }

        $userWord = UserWord::firstOrCreate([
            'user_id' => $user->id,
            'word_id' => $word->id,
        ]);

        return response()->json([
            'id' => $word->id,
            'lemma' => $word->lemma,
            'gender' => $word->gender,
            'plural' => $word->plural,
            'example_sentence' => $word->example_sentence,
            'example_translation' => $word->example_translation,
            'due_at' => $userWord->due_at?->format('d.m.Y H:i'),
        ]);
    }

    public function answer(Request $request): JsonResponse
    {
        $data = $request->validate([
            'word_id' => 'required|exists:words,id',
            'result' => 'required|in:hard,medium,easy',
        ]);

        $user = $request->user();
        $word = Word::findOrFail($data['word_id']);

        $result = $this->srs->review($user, $word, $data['result']);

        $next = $this->next()->getData(true);

        return response()->json([
            'next_due_at' => $result['dueDate']->format('d.m.Y H:i'),
            'next_interval' => $result['nextInterval'],
            'next_card' => $next,
        ]);
    }
}
