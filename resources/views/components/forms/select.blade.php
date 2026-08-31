@props([
    'name',
    'label' => null,
    'options' => [],
    'value' => null,
    'required' => false,
    'placeholder' => 'Selecione...',
    'optionValue' => 'id',
    'optionLabel' => 'name',
])

<div class="mb-5">

    @if($label)

        <x-forms.label
            :for="$attributes->get('id', $name)"
            :required="$required"
        >
            {{ $label }}
        </x-forms.label>

    @endif

    <select
        id="{{ $attributes->get('id', $name) }}"
        name="{{ $name }}"
        @if($required)
            required
        @endif
        {{ $attributes->merge([
            'class' =>
                'block w-full rounded-lg border border-slate-300 px-3 py-2 shadow-sm transition
                focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500
                ' . ($errors->has($name)
                    ? 'border-red-500 focus:border-red-500 focus:ring-red-500'
                    : ''),
        ]) }}
    >

        <option value="">
            {{ $placeholder }}
        </option>

        @foreach($options as $option)

            <option
                value="{{ $option->{$optionValue} }}"
                @selected(old($name, $value) == $option->{$optionValue})
            >
                {{ $option->{$optionLabel} }}
            </option>

        @endforeach

    </select>

    <x-forms.error :name="$name"/>

</div>
