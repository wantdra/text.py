@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'mt-1 block w-full rounded-lg bg-slate-900/60 border border-slate-600 text-slate-100 focus:border-amber-400 focus:ring-amber-400']) !!}>
