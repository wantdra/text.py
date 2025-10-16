<div class="space-y-6" x-data="{}">
    @if($card)
        <div>
            <h2 class="text-3xl font-bold mb-2">{{ $card['lemma'] }}</h2>
            <p class="text-lg text-amber-400 mb-1">
                {{ ['m' => 'der', 'f' => 'die', 'n' => 'das'][$card['gender']] ?? '' }}
            </p>
            <p class="text-lg mb-4">Çoğul: <span class="font-semibold">{{ $card['plural'] }}</span></p>
            <p class="text-slate-200">{{ $card['example_sentence'] }}</p>
            <p class="text-slate-400 italic">{{ $card['example_translation'] }}</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <button wire:click="answer('hard')" class="bg-red-500 hover:bg-red-600 py-4 rounded-xl text-xl font-bold">ZOR</button>
            <button wire:click="answer('medium')" class="bg-amber-500 hover:bg-amber-600 py-4 rounded-xl text-xl font-bold">ORTA</button>
            <button wire:click="answer('easy')" class="bg-emerald-500 hover:bg-emerald-600 py-4 rounded-xl text-xl font-bold">KOLAY</button>
        </div>
        @if($status)
            <p class="text-sm text-slate-400">{{ $status }}</p>
        @endif
    @else
        <p>{{ $status }}</p>
    @endif
</div>
