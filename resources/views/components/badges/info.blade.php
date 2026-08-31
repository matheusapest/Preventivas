<span
    {{ $attributes->merge([
        'class' => 'inline-flex items-center rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700',
    ]) }}
>
    {{ $slot }}
</span>
