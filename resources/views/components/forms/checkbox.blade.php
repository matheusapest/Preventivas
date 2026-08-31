@props([
    'name',
    'label',
    'checked' => false,
])

<div class="mb-5">

    <label class="inline-flex items-center gap-3">

        <input
            type="checkbox"
            id="{{ $name }}"
            name="{{ $name }}"
            value="1"

            @checked(old($name, $checked))

            {{ $attributes->merge([
                'class' =>
                    'h-4 w-4 rounded border-slate-300 text-blue-600
                    focus:ring-blue-500',
            ]) }}
        >

        <span class="text-sm font-medium text-slate-700">

            {{ $label }}

        </span>

    </label>

    <x-forms.error :name="$name"/>

</div>
