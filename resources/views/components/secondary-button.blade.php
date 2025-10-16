<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex justify-center rounded-lg border border-slate-500 px-4 py-2 font-semibold text-slate-200 hover:bg-slate-700 transition']) }}>
    {{ $slot }}
</button>
