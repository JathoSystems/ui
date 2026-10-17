@props([
    'variant' => 'info',
    'title' => null,
    'dismissible' => false,
])

@php
    [$variantClasses, $icon] = match ($variant) {
        'success' => ['bg-green-50 border-green-300 text-green-800 dark:bg-green-900/20 dark:border-green-800 dark:text-green-300', 'circle-check'],
        'warning' => ['bg-yellow-50 border-yellow-300 text-yellow-800 dark:bg-yellow-900/20 dark:border-yellow-800 dark:text-yellow-300', 'triangle-alert'],
        'error' => ['bg-red-50 border-red-300 text-red-800 dark:bg-red-900/20 dark:border-red-800 dark:text-red-300', 'circle-x'],
        default => ['bg-blue-50 border-blue-300 text-blue-800 dark:bg-blue-900/20 dark:border-blue-800 dark:text-blue-300', 'info'],
    };
@endphp

<div
    role="{{ $variant === 'error' ? 'alert' : 'status' }}"
    @if ($dismissible) x-data="{ visible: true }" x-show="visible" x-transition.opacity @endif
    {{ $attributes->class(['flex items-start gap-3 rounded-md border px-3 py-2 text-sm', $variantClasses]) }}
>
    <x-dynamic-component :component="'lucide-'.$icon" class="size-5 shrink-0 mt-px" />

    <div class="flex-1 min-w-0">
        @if ($title)
            <p class="font-semibold">{{ $title }}</p>
        @endif
        <div>{{ $slot }}</div>
    </div>

    @if ($dismissible)
        <button type="button" @click="visible = false" aria-label="{{ __('ui::ui.close') }}"
            class="shrink-0 rounded p-0.5 opacity-70 hover:opacity-100 cursor-pointer">
            <x-lucide-x class="size-4" />
        </button>
    @endif
</div>
