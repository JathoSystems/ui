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
    {{-- Without Livewire, an unchecked box still submits "0" --}}
    @if (! $isLivewire && $name)
        <input type="hidden" name="{{ $name }}" value="0">
    @endif
    <input type="checkbox"
        {{ $attributes->merge(['name' => $name, 'value' => '1'])->class('size-4 rounded border text-blue-600 focus:ring-blue-500 dark:bg-zinc-900') }}>
</x-ui::field>
