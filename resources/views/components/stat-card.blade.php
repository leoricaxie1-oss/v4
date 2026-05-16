@props(['label', 'value', 'icon' => null, 'tone' => 'brand'])
@php
    $tones = [
        'brand'   => 'bg-brand-50 text-brand-700',
        'success' => 'bg-emerald-50 text-emerald-700',
        'warn'    => 'bg-amber-50 text-amber-700',
        'danger'  => 'bg-rose-50 text-rose-700',
        'gray'    => 'bg-slate-100 text-slate-700',
    ];
@endphp
<div class="card">
    <div class="card-body flex items-center gap-4">
        <div class="grid h-12 w-12 place-items-center rounded-lg {{ $tones[$tone] ?? $tones['brand'] }} text-xl font-bold">
            {{ $icon ?? '★' }}
        </div>
        <div>
            <p class="text-xs font-medium uppercase tracking-wide text-slate-500">{{ $label }}</p>
            <p class="text-2xl font-bold text-slate-900">{{ $value }}</p>
        </div>
    </div>
</div>
