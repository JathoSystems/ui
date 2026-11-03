@props([
    'item',
    'as' => 'div',
])

<{{ $as }} wire:sort:item="{{ $item }}" {{ $attributes }}>
    {{ $slot }}
</{{ $as }}>
