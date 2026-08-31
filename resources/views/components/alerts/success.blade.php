@props([
    'title' => null,
])

<div
    {{ $attributes->merge([
        'class' => 'rounded-lg border border-green-200 bg-green-50 p-4 text-green-800',
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
