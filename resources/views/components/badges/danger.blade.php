<span
    {{ $attributes->merge([
        'class' => 'inline-flex items-center rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700',
    ]) }}
>
    {{ $slot }}
</span>
