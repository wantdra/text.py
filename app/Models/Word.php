<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Word extends Model
{
    use HasFactory;

    protected $fillable = [
        'lemma',
        'gender',
        'plural',
        'level',
        'example_sentence',
        'example_translation',
        'tags',
    ];

    protected $casts = [
        'tags' => 'array',
    ];

    public function userWords()
    {
        return $this->hasMany(UserWord::class);
    }

    public function reviewLogs()
    {
        return $this->hasMany(ReviewLog::class);
    }

    public function scopeForGamePool($query, string $type)
    {
        return $query->when($type === 'artikel', fn ($q) => $q->whereIn('gender', ['m', 'f', 'n']))
            ->when($type === 'plural', fn ($q) => $q->whereNotNull('plural'))
            ->when($type === 'cloze', fn ($q) => $q->whereNotNull('example_sentence'));
    }
}
