@props([
    'name',
    'id' => null,
    'label' => null,
    'placeholder' => null,
    'rows' => 4,
    'required' => false,
])

<div class="w-full">

    @if ($label)

        <label
            for="{{ $id ?? $name }}"
            class="mb-1.5 block text-sm font-medium text-slate-700"
        >
            {{ $label }}

            @if ($required)
                <span class="text-red-500">*</span>
            @endif
        </label>

    @endif

    <textarea
        id="{{ $id ?? $name }}"
        name="{{ $name }}"
        rows="{{ $rows }}"
        placeholder="{{ $placeholder }}"
        @required($required)
        {{ $attributes->merge([
            'class' => 'block w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-700 shadow-sm placeholder:text-slate-400 focus:border-slate-500 focus:outline-none focus:ring-1 focus:ring-slate-500',
        ]) }}
    >{{ old($name, $slot) }}</textarea>

    @error($name)
        <p class="mt-1.5 text-xs text-red-600">
            {{ $message }}
        </p>
    @enderror

</div>
