<x-layouts.app>
    <div class="max-w-6xl mx-auto py-12 px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-slate-800 rounded-xl p-6 shadow">
                <h2 class="text-lg font-semibold mb-2">Bugün Due</h2>
                <p class="text-4xl font-bold" id="due-today">0</p>
            </div>
            <div class="bg-slate-800 rounded-xl p-6 shadow">
                <h2 class="text-lg font-semibold mb-2">Son 7 Gün</h2>
                <canvas id="review-chart" class="w-full h-40"></canvas>
            </div>
        </div>
        <div class="mt-10 text-center">
            <a href="{{ route('learn') }}" class="inline-block bg-amber-500 hover:bg-amber-600 text-slate-900 font-bold py-4 px-10 rounded-full text-xl">Öğrenmeye Başla</a>
        </div>
    </div>
</x-layouts.app>
