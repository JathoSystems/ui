@props([
    'name' => null,
    'label' => null,
    'help' => null,
    'multiple' => false,
    'accept' => null,
    'containerClass' => '',
])

{{--
    <x-ui::dropzone wire:model="uploads" multiple accept="image/*,video/*" label="Media" />
--}}

@php
    $name ??= $attributes->wire('model')->value() ?: null;
    $inputId = 'dropzone-'.md5(($name ?? '').uniqid());
@endphp

<x-ui::field :name="$name" :label="$label" :help="$help" :class="$containerClass" :wraps-control="false">
    <div
        x-data="{
            dragging: false,
            uploading: false,
            progress: 0,
            failed: false,
            drop(files) {
                this.dragging = false
                if (! files.length) return
                this.$refs.input.files = files
                this.$refs.input.dispatchEvent(new Event('change', { bubbles: true }))
            },
        }"
        x-on:livewire-upload-start="uploading = true; failed = false; progress = 0"
        x-on:livewire-upload-progress="progress = $event.detail.progress"
        x-on:livewire-upload-finish="uploading = false"
        x-on:livewire-upload-error="uploading = false; failed = true"
        x-on:livewire-upload-cancel="uploading = false"
        @dragover.prevent="dragging = true"
        @dragleave.prevent="dragging = false"
        @drop.prevent="drop($event.dataTransfer.files)"
        class="mt-1"
    >
        <label for="{{ $inputId }}"
            :class="dragging && 'border-blue-500 bg-blue-50 dark:bg-blue-900/20'"
            class="flex flex-col items-center gap-2 rounded-md border-2 border-dashed px-4 py-6 text-center text-sm cursor-pointer">
            <x-lucide-upload class="size-6 opacity-60" />
            <span>
                <span class="font-medium text-blue-600 dark:text-blue-400">{{ __('ui::ui.browse') }}</span>
                {{ __('ui::ui.or_drop_files') }}
            </span>
            @if ($accept)
                <span class="text-xs opacity-60">{{ $accept }}</span>
            @endif
        </label>

        <input type="file" id="{{ $inputId }}" x-ref="input"
            @if ($multiple) multiple @endif
            @if ($accept) accept="{{ $accept }}" @endif
            {{ $attributes->except(['multiple', 'accept'])->class('sr-only') }}>

        <div x-show="uploading" x-cloak class="mt-2" role="progressbar" :aria-valuenow="progress" aria-valuemin="0" aria-valuemax="100">
            <div class="h-1.5 rounded-full bg-gray-200 dark:bg-zinc-700 overflow-hidden">
                <div class="h-full bg-blue-500 transition-[width]" :style="{ width: progress + '%' }"></div>
            </div>
            <p class="mt-1 text-xs opacity-60">{{ __('ui::ui.uploading') }} <span x-text="progress + '%'"></span></p>
        </div>

        <p x-show="failed" x-cloak class="mt-2 text-sm text-red-500">{{ __('ui::ui.upload_failed') }}</p>

        @if ($slot->isNotEmpty())
            <div class="mt-3">{{ $slot }}</div>
        @endif
    </div>
</x-ui::field>
