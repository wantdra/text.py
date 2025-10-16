<?php

namespace App\Http\Controllers;

use App\Models\GameScore;
use App\Models\UserWord;
use App\Models\Word;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GameController extends Controller
{
    public function pool(Request $request): JsonResponse
    {
        $request->validate([
            'type' => 'required|in:artikel,plural,cloze,dragdrop',
        ]);

        $user = $request->user();

        $leechIds = UserWord::where('user_id', $user->id)
            ->where('leech_count', '>=', 5)
            ->pluck('word_id')
            ->toArray();

        $query = Word::query()->forGamePool($request->string('type'));

        if (! empty($leechIds)) {
            $words = Word::whereIn('id', $leechIds)->inRandomOrder()->limit(10)->get();
            $remaining = $query->whereNotIn('id', $leechIds)->inRandomOrder()->limit(10)->get();
            $collection = $words->merge($remaining)->take(20);
        } else {
            $collection = $query->inRandomOrder()->limit(20)->get();
        }

        return response()->json($collection->map(fn ($word) => [
            'id' => $word->id,
            'lemma' => $word->lemma,
            'gender' => $word->gender,
            'plural' => $word->plural,
            'example_sentence' => $word->example_sentence,
            'example_translation' => $word->example_translation,
        ]));
    }

    public function score(Request $request): JsonResponse
    {
        $data = $request->validate([
            'game_type' => 'required|string',
            'score' => 'required|integer',
            'duration_sec' => 'required|integer',
        ]);

        $user = $request->user();

        $record = GameScore::create([
            'user_id' => $user->id,
            'game_type' => $data['game_type'],
            'score' => $data['score'],
            'duration_sec' => $data['duration_sec'],
            'created_at' => now(),
        ]);

        return response()->json(['id' => $record->id]);
    }
}
