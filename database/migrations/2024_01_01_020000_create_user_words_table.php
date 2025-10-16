<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_words', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('word_id')->constrained()->cascadeOnDelete();
            $table->float('ease')->default(2.3);
            $table->unsignedInteger('streak')->default(0);
            $table->enum('last_result', ['hard', 'medium', 'easy'])->nullable();
            $table->unsignedInteger('interval_minutes')->default(0);
            $table->dateTime('due_at')->nullable()->index();
            $table->unsignedInteger('review_count')->default(0);
            $table->unsignedTinyInteger('leech_count')->default(0);
            $table->timestamps();
            $table->unique(['user_id', 'word_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_words');
    }
};
