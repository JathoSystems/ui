@props([
    'handler',
    'as' => 'div',
])

{{--
    Drag-to-reorder list on top of Livewire 4's wire:sort.
    The handler is called as handler($key, $position), with a zero-based position.

    <x-ui::sortable handler="moveItem" class="flex flex-col gap-2">
        @foreach ($items as $item)
            <x-ui::sortable.item :item="$item->id" wire:key="item-{{ $item->id }}">
                <x-ui::sortable.handle /> {{ $item->name }}
            </x-ui::sortable.item>
        @endforeach
    </x-ui::sortable>

    When any item contains a handle, only the handle starts a drag.
--}}

<{{ $as }} wire:sort="{{ $handler }}" {{ $attributes }}>
    {{ $slot }}
</{{ $as }}>
