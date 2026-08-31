@props([
    'type' => 'button',
    'href' => null,
    'class' => '',
])

@php
    $baseClasses = 'inline-flex items-center justify-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white transition duration-200 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2';
@endphp

@if ($href)

    <a
        href="{{ $href }}"
        {{ $attributes->class([$baseClasses, $class])->merge() }}
    >
        {{ $slot }}
    </a>

@else

    <button
        type="{{ $type }}"
        {{ $attributes->class([$baseClasses, 'disabled:cursor-not-allowed disabled:opacity-50' => true, $class])->merge() }}
    >
        {{ $slot }}
    </button>

@endif
