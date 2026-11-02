@props([
    'name' => null,
    'label' => null,
    'help' => null,
    'options' => [],
    'containerClass' => '',
])

{{--
    Button-group picker for a small set of preset values.

    <x-ui::segmented label="Columns" wire:model.live="settings.columns" :options="[2 => '2', 3 => '3', 4 => '4']" />
--}}

@php
    $model = $attributes->wire('model')->value();
    $name ??= $model ?: null;
    $radioName = $name ?? 'segmented-'.uniqid();
@endphp

<x-ui::field :name="$name" :label="$label" :help="$help" :class="$containerClass" :wraps-control="false">
    <div role="radiogroup" @if ($label) aria-label="{{ $label }}" @endif
        class="mt-1 inline-flex flex-wrap rounded-md border bg-gray-100 dark:bg-zinc-900 p-0.5 gap-0.5">
        @foreach ($options as $optionValue => $optionLabel)
            <label class="relative cursor-pointer">
                <input type="radio" name="{{ $radioName }}" value="{{ $optionValue }}"
                    {{ $attributes->whereStartsWith('wire:model') }}
                    {{ $attributes->only(['disabled']) }}
                    class="peer sr-only">
                <span class="block px-3 py-1 text-sm rounded transition-colors text-gray-600 dark:text-gray-300 hover:text-black dark:hover:text-white peer-checked:bg-white peer-checked:text-black peer-checked:shadow-sm dark:peer-checked:bg-zinc-700 dark:peer-checked:text-white peer-focus-visible:ring-2 peer-focus-visible:ring-blue-500 peer-disabled:opacity-50 peer-disabled:cursor-not-allowed">
                    {{ $optionLabel }}
                </span>
            </label>
        @endforeach
    </div>
</x-ui::field>
