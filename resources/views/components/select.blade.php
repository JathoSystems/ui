@props([
    'name' => null,
    'label' => null,
    'help' => null,
    'options' => [],
    'placeholder' => null,
    'containerClass' => '',
])

@php
    $name ??= $attributes->wire('model')->value() ?: null;
    $hasError = $name && $errors->has($name);
@endphp

<x-ui::field :name="$name" :label="$label" :help="$help" :required="$attributes->has('required')" :class="$containerClass">
    <select
        {{ $attributes->merge([
            'name' => $name,
            'aria-invalid' => $hasError ? 'true' : null,
        ])->class([
            'p-2 mt-1 block w-full border rounded-md focus:border-blue-500 focus:ring-blue-500 dark:bg-zinc-900 dark:text-white outline-none',
            'border-red-500' => $hasError,
        ]) }}>
        @if ($placeholder !== null)
            <option value="">{{ $placeholder }}</option>
        @endif
        @foreach ($options as $optionValue => $optionLabel)
            <option value="{{ $optionValue }}">{{ $optionLabel }}</option>
        @endforeach
        {{ $slot }}
    </select>
</x-ui::field>
