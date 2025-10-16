<div class="space-y-4">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="md:col-span-1 bg-slate-700 rounded-xl p-4 space-y-2">
            <h3 class="font-semibold">Kelime Havuzu</h3>
            @foreach($items as $item)
                <div class="bg-slate-600 px-3 py-2 rounded flex items-center justify-between">
                    <span>{{ $item['lemma'] }}</span>
                    <div class="space-x-2 text-xs">
                        <button wire:click="drop('der', {{ $item['id'] }})" class="bg-amber-500 text-slate-900 px-2 py-1 rounded">der</button>
                        <button wire:click="drop('die', {{ $item['id'] }})" class="bg-amber-500 text-slate-900 px-2 py-1 rounded">die</button>
                        <button wire:click="drop('das', {{ $item['id'] }})" class="bg-amber-500 text-slate-900 px-2 py-1 rounded">das</button>
                    </div>
                </div>
            @endforeach
        </div>
        @foreach(['der' => 'Maskulin', 'die' => 'Feminin', 'das' => 'Neutral'] as $artikel => $title)
            <div class="bg-slate-700 rounded-xl p-4 space-y-2">
                <h3 class="font-semibold">{{ $title }}</h3>
                @foreach($columns[$artikel] as $item)
                    <div class="bg-slate-600 px-3 py-2 rounded">{{ $item['lemma'] }}</div>
                @endforeach
            </div>
        @endforeach
    </div>
    <div class="text-right">
        <span class="font-semibold">Skor: {{ $score }}</span>
    </div>
</div>
