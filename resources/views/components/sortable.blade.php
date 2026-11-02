@props([
    'handler',
    'as' => 'div',
])

{{--
    Calls handler($item, $position) with a zero-based position. With handles, only a handle starts a drag.

    <x-ui::sortable handler="moveItem" class="flex flex-col gap-2">
        @foreach ($items as $item)
            <x-ui::sortable.item :item="$item->id" wire:key="item-{{ $item->id }}">
                <x-ui::sortable.handle /> {{ $item->name }}
            </x-ui::sortable.item>
        @endforeach
    </x-ui::sortable>
--}}

<{{ $as }} wire:sort="{{ $handler }}" {{ $attributes }}>
    {{ $slot }}
</{{ $as }}>
