@props([
    'name' => null,
    'label' => null,
    'type' => 'text',
    'help' => null,
    'containerClass' => '',
    'inputContainerClass' => '',
])

@php
    $name ??= $attributes->wire('model')->value() ?: null;
    $hasError = $name && $errors->has($name);
@endphp

<x-ui::field :name="$name" :label="$label" :help="$help" :required="$attributes->has('required')" :class="$containerClass">
    <div @class([$inputContainerClass, 'mt-1'])>
        <input type="{{ $type }}"
            {{ $attributes->merge([
                'name' => $name,
                'step' => $type === 'number' ? 'any' : null,
                'aria-invalid' => $hasError ? 'true' : null,
            ])->class([
                'p-2 block w-full border rounded-md focus:border-blue-500 focus:ring-blue-500 dark:bg-zinc-900 dark:text-white outline-none',
                'border-red-500' => $hasError,
            ]) }}>

        {{ $slot }}
    </div>
</x-ui::field>
