<?php

namespace App\Services;

use App\Models\ReviewLog;
use App\Models\User;
use App\Models\UserWord;
use App\Models\Word;
use Carbon\Carbon;

class SrsService
{
    /**
     * Handle SRS state transitions based on user answer.
     */
    public function review(User $user, Word $word, string $result): array
    {
        $userWord = UserWord::firstOrCreate([
            'user_id' => $user->id,
            'word_id' => $word->id,
        ], [
            'ease' => 2.3,
            'streak' => 0,
        ]);

        $now = Carbon::now();
        $prevInterval = $userWord->interval_minutes;

        // Determine the next interval according to configured progressions.
        $progressions = [
            'hard' => [1, 5, 10, 60],
            'medium' => [10, 25, 60, 480, 2880],
            'easy' => [1440, 4320, 10080, 30240, 86400],
        ];

        $currentIndex = array_search($prevInterval, $progressions[$result], true);
        if ($prevInterval === 0 || $currentIndex === false) {
            $currentIndex = -1;
        }
        $nextIndex = min($currentIndex + 1, count($progressions[$result]) - 1);
        $nextInterval = $progressions[$result][$nextIndex];

        // Adjust ease and streak according to answer type.
        if ($result === 'hard') {
            // Hard answers slightly reduce ease but never below 1.7, streak unchanged.
            $userWord->ease = max(1.7, $userWord->ease - 0.05);
            $userWord->leech_count = min(255, $userWord->leech_count + 1);
        } elseif ($result === 'medium') {
            // Medium answers keep ease stable and increase streak steadily.
            $userWord->streak += 1;
        } else {
            // Easy answers reward with higher ease but cap at 3.0.
            $userWord->streak += 1;
            $userWord->ease = min(3.0, $userWord->ease + 0.05);
        }

        if ($result !== 'hard') {
            $userWord->leech_count = 0;
        }

        // Detect leech: after 5 hard results keep interval short.
        if ($userWord->leech_count >= 5) {
            $nextInterval = 10;
        }

        // Save computed interval and due date.
        $userWord->interval_minutes = $nextInterval;
        $userWord->due_at = $now->copy()->addMinutes($nextInterval);
        $userWord->last_result = $result;
        $userWord->review_count += 1;
        $userWord->save();

        ReviewLog::create([
            'user_id' => $user->id,
            'word_id' => $word->id,
            'result' => $result,
            'reviewed_at' => $now,
            'prev_interval' => $prevInterval,
            'next_interval' => $nextInterval,
        ]);

        return [
            'userWord' => $userWord,
            'nextInterval' => $nextInterval,
            'dueDate' => $userWord->due_at,
        ];
    }
}
