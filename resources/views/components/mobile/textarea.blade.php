@props([
    'label',
    'name',
    'value' => null,
    'placeholder' => null,
    'required' => false,
    'rows' => 4,
    'help' => null,
])

@php
    $fieldId = $attributes->get('id', $name);

    $hasError = $errors->has($name);

    $textareaClasses = $hasError
        ? 'border-red-300 bg-red-50 focus:border-red-500 focus:ring-red-500'
        : 'border-slate-300 bg-white focus:border-blue-500 focus:ring-blue-500';
@endphp

<div class="w-full">

    <label
        for="{{ $fieldId }}"
        class="mb-1.5 block text-sm font-medium text-slate-700"
    >
        {{ $label }}

        @if ($required)
            <span class="text-red-500">*</span>
        @endif
    </label>


    <textarea
        id="{{ $fieldId }}"
        name="{{ $name }}"
        rows="{{ $rows }}"
        placeholder="{{ $placeholder }}"
        @required($required)
        {{ $attributes->merge([
            'class' =>
                'w-full rounded-xl border px-4 py-3 text-base text-slate-900 outline-none transition focus:ring-2 ' .
                $textareaClasses,
        ])->except(['id']) }}
    >{{ old($name, $value) }}</textarea>


    @if ($help && ! $hasError)

        <p class="mt-1.5 text-xs text-slate-500">
            {{ $help }}
        </p>

    @endif


    @error($name)

        <p class="mt-1.5 text-xs font-medium text-red-600">
            {{ $message }}
        </p>

    @enderror

</div>
