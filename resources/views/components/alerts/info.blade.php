@props([
    'title' => null,
])

<div
    {{ $attributes->merge([
        'class' => 'rounded-lg border border-blue-200 bg-blue-50 p-4 text-blue-800',
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
