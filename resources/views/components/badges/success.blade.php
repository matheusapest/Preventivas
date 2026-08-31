<span
    {{ $attributes->merge([
        'class' => 'inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700',
    ]) }}
>
    {{ $slot }}
</span>
