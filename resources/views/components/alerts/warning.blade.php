@props([
    'title' => null,
])

<div
    {{ $attributes->merge([
        'class' => 'rounded-lg border border-amber-200 bg-amber-50 p-4 text-amber-800',
    ]) }}
>

    @if($title)

        <h3 class="mb-1 font-semibold">

            {{ $title }}

        </h3>

    @endif

    <div>

        {{ $slot }}

    </div>

</div>
