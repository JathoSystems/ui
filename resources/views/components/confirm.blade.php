@props([
    'title' => null,
    'message' => null,
    'confirmLabel' => null,
    'cancelLabel' => null,
    'color' => 'danger',
    'trigger' => null,
])

<x-ui::modal :title="$title ?? __('ui::ui.confirm_title')" class="max-w-md">
    <x-slot:trigger>{{ $trigger }}</x-slot:trigger>

    @if ($message)
        <p class="text-sm text-gray-700 dark:text-gray-300">{{ $message }}</p>
    @endif
    {{ $slot }}

    <div class="flex flex-row justify-end gap-2 mt-4">
        <x-ui::button color="neutral" @click="closeModal()">
            {{ $cancelLabel ?? __('ui::ui.cancel') }}
        </x-ui::button>
        <x-ui::button :color="$color" @click="closeModal()" :attributes="$attributes">
            {{ $confirmLabel ?? __('ui::ui.confirm') }}
        </x-ui::button>
    </div>
</x-ui::modal>
