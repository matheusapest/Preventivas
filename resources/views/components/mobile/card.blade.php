@props([
    'title' => null,
    'subtitle' => null,
])

<div
    {{ $attributes->merge([
        'class' => 'rounded-xl border border-slate-200 bg-white p-4 shadow-sm',
    ]) }}
>

    @if ($title || $subtitle)

        <div class="mb-4">

            @if ($title)

                <h2 class="text-base font-semibold text-slate-900">
                    {{ $title }}
                </h2>

            @endif

            @if ($subtitle)

                <p class="mt-1 text-sm text-slate-500">
                    {{ $subtitle }}
                </p>

            @endif

        </div>

    @endif

    {{ $slot }}

</div>
