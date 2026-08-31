@props([
    'title' => null,
    'description' => null,
])

<section
    {{ $attributes->merge([
        'class' => 'space-y-3',
    ]) }}
>

    @if ($title || $description)

        <div>

            @if ($title)

                <h2 class="text-base font-semibold text-slate-900">
                    {{ $title }}
                </h2>

            @endif

            @if ($description)

                <p class="mt-1 text-sm text-slate-500">
                    {{ $description }}
                </p>

            @endif

        </div>

    @endif

    {{ $slot }}

</section>
