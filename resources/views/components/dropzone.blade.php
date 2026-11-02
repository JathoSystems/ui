@props([
    'name' => null,
    'label' => null,
    'help' => null,
    'multiple' => false,
    'accept' => null,
    'containerClass' => '',
])

{{--
    Drag-and-drop file picker bound to a Livewire WithFileUploads property.

    <x-ui::dropzone wire:model="uploads" multiple accept="image/*,video/*" label="Media" />

    Shows upload progress via Livewire's upload events. Pass previews in the default slot.
--}}

@php
    $model = $attributes->wire('model')->value();
    $name ??= $model ?: null;
    $inputId = 'dropzone-'.md5(($name ?? '').uniqid());
@endphp

<x-ui::field :name="$name" :label="$label" :help="$help" :class="$containerClass" :wraps-control="false">
    <div
        x-data="{
            dragging: false,
            uploading: false,
            progress: 0,
            failed: false,
            drop(event) {
                this.dragging = false;
                const files = event.dataTransfer?.files;
                if (! files || ! files.length) return;
                const input = this.$refs.input;
                const transfer = new DataTransfer();
                Array.from(files)
                    .slice(0, input.multiple ? files.length : 1)
                    .forEach(file => transfer.items.add(file));
                input.files = transfer.files;
                input.dispatchEvent(new Event('change', { bubbles: true }));
            },
        }"
        x-on:livewire-upload-start="uploading = true; failed = false; progress = 0"
        x-on:livewire-upload-progress="progress = $event.detail.progress"
        x-on:livewire-upload-finish="uploading = false"
        x-on:livewire-upload-error="uploading = false; failed = true"
        x-on:livewire-upload-cancel="uploading = false"
        @dragover.prevent="dragging = true"
        @dragleave.prevent="dragging = false"
        @drop.prevent="drop($event)"
        class="mt-1"
    >
        <label for="{{ $inputId }}"
            :class="dragging ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' : 'border-gray-300 dark:border-zinc-600 hover:border-gray-400 dark:hover:border-zinc-500'"
            class="flex flex-col items-center justify-center gap-2 rounded-md border-2 border-dashed px-4 py-6 text-center text-sm cursor-pointer transition-colors">
            <x-lucide-upload class="size-6 text-gray-500 dark:text-gray-400" />
            <span>
                <span class="font-medium text-blue-600 dark:text-blue-400">{{ __('ui::ui.browse') }}</span>
                {{ __('ui::ui.or_drop_files') }}
            </span>
            @if ($accept)
                <span class="text-xs text-gray-500 dark:text-gray-400">{{ $accept }}</span>
            @endif
        </label>

        <input type="file" id="{{ $inputId }}" x-ref="input"
            @if ($multiple) multiple @endif
            @if ($accept) accept="{{ $accept }}" @endif
            {{ $attributes->except(['multiple', 'accept'])->class('sr-only') }}>

        <div x-show="uploading" x-cloak class="mt-2" role="progressbar" :aria-valuenow="progress" aria-valuemin="0" aria-valuemax="100">
            <div class="h-1.5 w-full rounded-full bg-gray-200 dark:bg-zinc-700 overflow-hidden">
                <div class="h-full bg-blue-500 transition-[width]" :style="{ width: progress + '%' }"></div>
            </div>
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('ui::ui.uploading') }} <span x-text="progress + '%'"></span></p>
        </div>

        <p x-show="failed" x-cloak class="mt-2 text-sm text-red-500">{{ __('ui::ui.upload_failed') }}</p>

        @if ($slot->isNotEmpty())
            <div class="mt-3">{{ $slot }}</div>
        @endif
    </div>
</x-ui::field>
