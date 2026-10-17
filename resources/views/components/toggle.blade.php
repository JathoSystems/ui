@props([
    'name' => null,
    'label' => null,
    'help' => null,
    'containerClass' => '',
])

@php
    $name ??= $attributes->wire('model')->value() ?: null;
    $isLivewire = $attributes->wire('model')->value() !== null;
@endphp

<x-ui::field :name="$name" :label="$label" :help="$help" :class="$containerClass" inline>
    @if (! $isLivewire && $name)
        <input type="hidden" name="{{ $name }}" value="0">
    @endif
    <span class="relative inline-flex shrink-0 items-center">
        <input type="checkbox" role="switch"
            {{ $attributes->merge(['name' => $name, 'value' => '1'])->class('peer sr-only') }}>
        <span aria-hidden="true"
            class="h-6 w-11 rounded-full bg-gray-300 dark:bg-zinc-700 transition-colors cursor-pointer peer-checked:bg-blue-500 peer-focus-visible:ring-2 peer-focus-visible:ring-blue-500 peer-focus-visible:ring-offset-2 peer-disabled:opacity-50 peer-disabled:cursor-not-allowed"></span>
        <span aria-hidden="true"
            class="pointer-events-none absolute left-0.5 size-5 rounded-full bg-white shadow transition-transform peer-checked:translate-x-5"></span>
    </span>
</x-ui::field>
