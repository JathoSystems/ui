<button type="button" wire:sort:handle aria-label="{{ __('ui::ui.drag_to_reorder') }}" title="{{ __('ui::ui.drag_to_reorder') }}"
    {{ $attributes->class('flex items-center justify-center size-6 shrink-0 rounded cursor-grab active:cursor-grabbing text-gray-500 hover:bg-gray-200 dark:hover:bg-zinc-700') }}>
    @if ($slot->isNotEmpty())
        {{ $slot }}
    @else
        <x-lucide-grip-vertical class="size-4" />
    @endif
</button>
