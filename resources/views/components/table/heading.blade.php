@props([
    'sortable' => false,
    'direction' => null,
])

{{--
    For a sortable column, pass the current direction
    (null when sorted on another column) and the click action:

    <x-ui::table.heading sortable :direction="$sortBy === 'name' ? $sortDirection : null" wire:click="sort('name')">Naam</x-ui::table.heading>
--}}
<th scope="col"
    @if ($sortable) aria-sort="{{ match ($direction) { 'asc' => 'ascending', 'desc' => 'descending', default => 'none' } }}" @endif
    {{ $attributes->only('class')->class('px-4 py-3 text-left text-xs font-medium tracking-wider') }}>
    @if ($sortable)
        <button type="button" {{ $attributes->except('class')->class('group inline-flex items-center gap-1 cursor-pointer') }}>
            {{ $slot }}
            @if ($direction === 'asc')
                <x-lucide-chevron-up class="size-3.5" />
                <span class="sr-only">{{ __('ui::ui.sort_ascending') }}</span>
            @elseif ($direction === 'desc')
                <x-lucide-chevron-down class="size-3.5" />
                <span class="sr-only">{{ __('ui::ui.sort_descending') }}</span>
            @else
                <x-lucide-chevrons-up-down class="size-3.5 opacity-0 group-hover:opacity-50 transition-opacity" />
            @endif
        </button>
    @else
        {{ $slot }}
    @endif
</th>
