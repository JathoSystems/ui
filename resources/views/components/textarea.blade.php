@props([
    'name' => null,
    'label' => null,
    'help' => null,
    'containerClass' => '',
])

@php
    $name ??= $attributes->wire('model')->value() ?: null;
    $hasError = $name && $errors->has($name);
@endphp

<x-ui::field :name="$name" :label="$label" :help="$help" :required="$attributes->has('required')" :class="$containerClass">
    <textarea
        {{ $attributes->merge([
            'name' => $name,
            'rows' => 4,
            'aria-invalid' => $hasError ? 'true' : null,
        ])->class([
            'p-2 mt-1 block w-full border rounded-md focus:border-blue-500 focus:ring-blue-500 dark:bg-zinc-900 dark:text-white outline-none',
            'border-red-500' => $hasError,
        ]) }}>{{ $slot }}</textarea>
</x-ui::field>
