@props([
    'name',
    'label' => null,
    'type' => 'text',
    'value' => null,
    'required' => false,
    'help' => null,
])

<div class="mb-5">

    @if($label)

        <x-forms.label
            :for="$name"
            :required="$required"
        >

            {{ $label }}

        </x-forms.label>

    @endif

    <input
        id="{{ $name }}"
        name="{{ $name }}"
        type="{{ $type }}"
        value="{{ old($name, $value) }}"

        @required($required)

        {{ $attributes->merge([
            'class' =>
                'block w-full rounded-lg border border-slate-300 px-3 py-2 shadow-sm transition
                focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500
                ' . ($errors->has($name)
                    ? 'border-red-500 focus:border-red-500 focus:ring-red-500'
                    : ''),
        ]) }}
    >

    @if($help)

        <p class="mt-2 text-sm text-slate-500">

            {{ $help }}

        </p>

    @endif

    <x-forms.error
        :name="$name"
    />

</div>
