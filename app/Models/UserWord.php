<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserWord extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'word_id',
        'ease',
        'streak',
        'last_result',
        'interval_minutes',
        'due_at',
        'review_count',
        'leech_count',
    ];

    protected $casts = [
        'due_at' => 'datetime',
        'ease' => 'float',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function word(): BelongsTo
    {
        return $this->belongsTo(Word::class);
    }

    public function isLeech(): bool
    {
        return $this->leech_count >= 5;
    }
}
