<div class="space-y-4">
    @if(!$finished && isset($pool[$index]))
        <div class="bg-slate-700 rounded-xl p-6">
            <h3 class="text-2xl font-semibold mb-2">{{ $pool[$index]['lemma'] }}</h3>
            <p class="text-sm text-slate-300">{{ $pool[$index]['example_translation'] ?? '' }}</p>
        </div>
        <div class="flex gap-3">
            <input type="text" wire:model="answer" class="flex-1 px-4 py-3 rounded-lg text-slate-900" placeholder="Çoğulu yazın">
            <button wire:click="submit" class="bg-emerald-500 hover:bg-emerald-600 px-4 py-3 rounded-lg font-semibold">Gönder</button>
        </div>
    @else
        <div class="text-center">
            <p class="text-3xl font-bold">Skor: {{ $score }}</p>
        </div>
    @endif
    @if($feedback)
        <p class="text-slate-300">{{ $feedback }}</p>
    @endif
</div>
