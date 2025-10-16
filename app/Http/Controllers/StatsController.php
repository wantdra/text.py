<?php

namespace App\Http\Controllers;

use App\Models\ReviewLog;
use App\Models\UserWord;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StatsController extends Controller
{
    public function overview(): JsonResponse
    {
        $user = Auth::user();
        $today = Carbon::today();

        $todayCount = ReviewLog::where('user_id', $user->id)
            ->whereDate('reviewed_at', $today)
            ->count();

        $totalCount = ReviewLog::where('user_id', $user->id)->count();

        $correctCount = ReviewLog::where('user_id', $user->id)
            ->whereIn('result', ['medium', 'easy'])
            ->count();

        $accuracy = $totalCount > 0 ? round($correctCount / $totalCount * 100, 1) : 0;

        $hardest = UserWord::with('word')
            ->where('user_id', $user->id)
            ->orderByDesc('leech_count')
            ->limit(5)
            ->get()
            ->map(fn ($item) => [
                'lemma' => $item->word->lemma,
                'leech_count' => $item->leech_count,
                'due_at' => optional($item->due_at)->format('d.m.Y H:i'),
            ]);

        $daily = ReviewLog::select(DB::raw('DATE(reviewed_at) as day'), DB::raw('count(*) as total'))
            ->where('user_id', $user->id)
            ->where('reviewed_at', '>=', $today->copy()->subDays(6))
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        return response()->json([
            'today' => $todayCount,
            'total' => $totalCount,
            'accuracy' => $accuracy,
            'hardest' => $hardest,
            'daily' => $daily,
        ]);
    }
}
