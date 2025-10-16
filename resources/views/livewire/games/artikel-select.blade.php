<div class="space-y-4">
    @if(!$finished && isset($pool[$index]))
        <div class="bg-slate-700 rounded-xl p-6">
            <h3 class="text-2xl font-semibold mb-2">{{ $pool[$index]['lemma'] }}</h3>
            <p class="text-sm text-slate-300">{{ $pool[$index]['example_sentence'] ?? '' }}</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <button wire:click="answer('m')" class="bg-amber-500 hover:bg-amber-600 py-3 rounded-lg text-xl font-bold">der</button>
            <button wire:click="answer('f')" class="bg-amber-500 hover:bg-amber-600 py-3 rounded-lg text-xl font-bold">die</button>
            <button wire:click="answer('n')" class="bg-amber-500 hover:bg-amber-600 py-3 rounded-lg text-xl font-bold">das</button>
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
