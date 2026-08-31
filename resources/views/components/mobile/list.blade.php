@props([
    'divided' => true,
])

<div
    {{ $attributes->merge([
        'class' => $divided
            ? 'divide-y divide-slate-200 overflow-hidden rounded-xl border border-slate-200 bg-white'
            : 'space-y-3',
    ]) }}
>
    {{ $slot }}
</div>
