@props([
    'position' => 'bottom-right',
    'duration' => 4000,
])

@php
    $positionClasses = match ($position) {
        'top-right' => 'top-4 right-4',
        'top-left' => 'top-4 left-4',
        'bottom-left' => 'bottom-4 left-4',
        default => 'bottom-4 right-4',
    };

    $sessionToast = session('toast');
@endphp

<div
    x-data="{
        toasts: [],
        nextId: 0,
        add({ message, variant = 'success', duration = @js($duration) }) {
            const id = this.nextId++;
            this.toasts.push({ id, message, variant });
            if (duration > 0) setTimeout(() => this.remove(id), duration);
        },
        remove(id) {
            this.toasts = this.toasts.filter(t => t.id !== id);
        },
        classesFor(variant) {
            return {
                success: 'border-green-300 dark:border-green-800',
                error: 'border-red-300 dark:border-red-800',
                warning: 'border-yellow-300 dark:border-yellow-800',
                info: 'border-blue-300 dark:border-blue-800',
            }[variant] ?? 'border-gray-300 dark:border-gray-700';
        },
    }"
    x-init="@if ($sessionToast) add(@js(is_array($sessionToast) ? $sessionToast : ['message' => $sessionToast])) @endif"
    x-on:toast.window="add(Array.isArray($event.detail) ? $event.detail[0] : $event.detail)"
    {{ $attributes->class(['fixed z-300 flex flex-col gap-2 w-80 max-w-[calc(100vw-2rem)] pointer-events-none', $positionClasses]) }}
    aria-live="polite"
>
    <template x-for="toast in toasts" :key="toast.id">
        <div
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            role="status"
            :class="classesFor(toast.variant)"
            class="pointer-events-auto flex items-start gap-2 rounded-md border-l-4 border bg-white dark:bg-zinc-800 px-3 py-2 text-sm text-black dark:text-white shadow-lg"
        >
            <span class="flex-1" x-text="toast.message"></span>
            <button type="button" @click="remove(toast.id)" aria-label="{{ __('ui::ui.close') }}"
                class="shrink-0 opacity-60 hover:opacity-100 cursor-pointer">
                <x-lucide-x class="size-4" />
            </button>
        </div>
    </template>
</div>
