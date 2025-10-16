@props(['status'])

@if ($status)
    <div class="mb-4 text-sm text-emerald-400">
        {{ $status }}
    </div>
@endif
