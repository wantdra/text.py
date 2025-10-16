<button {{ $attributes->merge(['type' => 'submit', 'class' => 'w-full inline-flex justify-center rounded-lg bg-amber-500 px-4 py-2 font-semibold text-slate-900 hover:bg-amber-400 transition']) }}>
    {{ $slot }}
</button>
