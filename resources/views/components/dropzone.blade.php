@props([
    'name' => null,
    'label' => null,
    'help' => null,
    'multiple' => false,
    'accept' => null,
    'containerClass' => '',
])

{{--
    Livewire:   <x-ui::dropzone wire:model="uploads" multiple accept="image/*,video/*" label="Media" />
    Plain form: <x-ui::dropzone name="cover_image" accept="image/*" label="Cover" />
--}}

@php
    $inputName = $name ? ($multiple && ! str_ends_with($name, '[]') ? $name.'[]' : $name) : null;
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
            files: [],
            drop(files) {
                this.dragging = false
                if (! files.length) return
                this.$refs.input.files = files
                this.$refs.input.dispatchEvent(new Event('change', { bubbles: true }))
            },
            select(input) {
                this.files.forEach((file) => file.preview && URL.revokeObjectURL(file.preview))
                this.files = Array.from(input.files).map((file) => ({
                    name: file.name,
                    size: this.formatSize(file.size),
                    preview: file.type.startsWith('image/') ? URL.createObjectURL(file) : null,
                }))
            },
            formatSize(bytes) {
                if (bytes < 1024) return bytes + ' B'
                if (bytes < 1024 * 1024) return Math.round(bytes / 1024) + ' kB'
                return (bytes / 1024 / 1024).toFixed(1) + ' MB'
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
        class="relative mt-1"
    >
        <input type="file" id="{{ $inputId }}" x-ref="input" @change="select($event.target)"
            @if ($inputName) name="{{ $inputName }}" @endif
            @if ($multiple) multiple @endif
            @if ($accept) accept="{{ $accept }}" @endif
            {{ $attributes->except(['multiple', 'accept'])->class('peer sr-only top-0 left-0') }}>

        <label for="{{ $inputId }}"
            :class="dragging && 'border-blue-500 bg-blue-50 dark:bg-blue-900/20'"
            class="flex flex-col items-center gap-2 rounded-md border-2 border-dashed px-4 py-6 text-center text-sm cursor-pointer transition-colors hover:border-blue-400 hover:bg-gray-50 dark:hover:bg-zinc-900 peer-focus-visible:border-blue-500 peer-focus-visible:ring-2 peer-focus-visible:ring-blue-500/40">
            <x-lucide-upload class="size-6 opacity-60" />
            <span>
                <span class="font-medium text-blue-600 dark:text-blue-400">{{ __('ui::ui.browse') }}</span>
                {{ __('ui::ui.or_drop_files') }}
            </span>
            @if ($accept)
                <span class="text-xs opacity-60">{{ $accept }}</span>
            @endif
        </label>

        <ul x-show="files.length" x-cloak class="mt-2 flex max-h-48 flex-col gap-1 overflow-y-auto text-sm" aria-live="polite">
            <template x-for="(file, index) in files" :key="index">
                <li class="flex items-center gap-2 rounded-md border px-2 py-1">
                    <img x-show="file.preview" :src="file.preview" alt="" class="size-8 shrink-0 rounded object-cover">
                    <span x-show="! file.preview" class="shrink-0 opacity-60"><x-lucide-file class="size-4" /></span>
                    <span class="flex-1 truncate text-left" x-text="file.name"></span>
                    <span class="shrink-0 text-xs opacity-60" x-text="file.size"></span>
                </li>
            </template>
        </ul>

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
