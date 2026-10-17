@props([
    'icon' => 'inbox',
    'title' => null,
])

<div {{ $attributes->class('flex flex-col items-center justify-center gap-2 py-10 px-4 text-center') }}>
    <div class="rounded-full bg-gray-100 dark:bg-zinc-900 p-3">
        <x-dynamic-component :component="'lucide-'.$icon" class="size-6 text-gray-500 dark:text-gray-400" />
    </div>
    <p class="font-semibold text-black dark:text-white">{{ $title ?? __('ui::ui.no_items') }}</p>
    @if ($slot->isNotEmpty())
        <div class="text-sm text-gray-600 dark:text-gray-400 max-w-sm">{{ $slot }}</div>
    @endif
    @isset($actions)
        <div class="flex flex-row flex-wrap justify-center gap-2 mt-2">{{ $actions }}</div>
    @endisset
</div>
