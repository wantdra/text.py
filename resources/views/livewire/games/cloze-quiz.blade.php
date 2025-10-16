<div class="space-y-4">
    @if(!$finished && isset($pool[$index]))
        <div class="bg-slate-700 rounded-xl p-6">
            @php
                $sentence = str_replace(['der ', 'die ', 'das '], '____ ', $pool[$index]['example_sentence']);
            @endphp
            <h3 class="text-xl font-semibold mb-4">{{ $sentence }}</h3>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <button wire:click="answer('der')" class="bg-amber-500 hover:bg-amber-600 py-3 rounded-lg font-semibold">der</button>
                <button wire:click="answer('die')" class="bg-amber-500 hover:bg-amber-600 py-3 rounded-lg font-semibold">die</button>
                <button wire:click="answer('das')" class="bg-amber-500 hover:bg-amber-600 py-3 rounded-lg font-semibold">das</button>
            </div>
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
