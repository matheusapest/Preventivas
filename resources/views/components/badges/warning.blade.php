<span
    {{ $attributes->merge([
        'class' => 'inline-flex items-center rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700',
    ]) }}
>
    {{ $slot }}
</span>
