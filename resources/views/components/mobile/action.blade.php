@props([
    'href' => null,
    'type' => 'button',
    'variant' => 'primary',
])

@php

    $variants = [

        'primary' =>
            'bg-blue-600 text-white hover:bg-blue-700',

        'secondary' =>
            'bg-slate-100 text-slate-700 hover:bg-slate-200',

        'success' =>
            'bg-emerald-600 text-white hover:bg-emerald-700',

        'danger' =>
            'bg-red-600 text-white hover:bg-red-700',

        'warning' =>
            'bg-amber-500 text-white hover:bg-amber-600',

    ];

    $variantClass =
        $variants[$variant]
        ?? $variants['primary'];

@endphp


@if ($href)

    <a
        href="{{ $href }}"
        {{ $attributes->merge([
            'class' =>
                'flex min-h-12 w-full items-center justify-center rounded-xl px-4 py-3 text-sm font-semibold transition ' .
                $variantClass,
        ]) }}
    >
        {{ $slot }}
    </a>

@else

    <button
        type="{{ $type }}"
        {{ $attributes->merge([
            'class' =>
                'flex min-h-12 w-full items-center justify-center rounded-xl px-4 py-3 text-sm font-semibold transition ' .
                $variantClass,
        ]) }}
    >
        {{ $slot }}
    </button>

@endif
