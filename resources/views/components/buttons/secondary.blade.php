@props([
    'type' => 'button',
    'href' => null,
])

@if ($href)

    <a
        href="{{ $href }}"
        {{ $attributes->merge([
            'class' => 'inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition duration-200 hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2',
        ]) }}
    >
        {{ $slot }}
    </a>

@else

    <button
        type="{{ $type }}"
        {{ $attributes->merge([
            'class' => 'inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition duration-200 hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50',
        ]) }}
    >
        {{ $slot }}
    </button>

@endif
