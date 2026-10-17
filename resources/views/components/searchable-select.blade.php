@props([
    'name' => null,
    'label' => null,
    'options' => [],
    'placeholder' => null,
    'value' => null,
    'help' => null,
    'containerClass' => '',
])

@php
    $name ??= $attributes->wire('model')->value() ?: null;
    $placeholder ??= __('ui::ui.search');

    $formattedOptions = collect($options)
        ->map(fn ($optionLabel, $optionValue) => ['value' => (string) $optionValue, 'label' => (string) $optionLabel])
        ->values()
        ->all();
@endphp

<x-ui::field :name="$name" :label="$label" :help="$help" :required="$attributes->has('required')" :class="$containerClass" :wraps-control="false"
    wire:key="searchable-select-{{ $name }}-{{ md5(json_encode($formattedOptions)) }}">
    <div
        wire:ignore
        class="relative mt-1"
        x-data="{
            open: false,
            search: '',
            activeIndex: 0,
            panelWidth: 0,
            selectedValue: @js($value !== null ? (string) $value : ''),
            options: @js($formattedOptions),
            placeholder: @js($placeholder),
            get selectedLabel() {
                const value = this.selectedValue === null || this.selectedValue === undefined ? '' : String(this.selectedValue);
                return this.options.find(o => o.value === value)?.label ?? this.placeholder;
            },
            get filteredOptions() {
                if (! this.search) return this.options;
                const q = this.search.toLowerCase();
                return this.options.filter(o => o.label.toLowerCase().includes(q));
            },
            openDropdown() {
                this.panelWidth = this.$refs.trigger.offsetWidth;
                this.open = true;
                this.activeIndex = 0;
                this.$nextTick(() => this.$refs.searchInput.focus());
            },
            close() {
                this.open = false;
                this.search = '';
            },
            move(step) {
                const count = this.filteredOptions.length;
                if (count === 0) return;
                this.activeIndex = (this.activeIndex + step + count) % count;
                this.$nextTick(() => this.$refs.list.children[this.activeIndex + 1]?.scrollIntoView({ block: 'nearest' }));
            },
            select(option) {
                if (! option) return;
                this.selectedValue = option.value === '' ? null : option.value;
                this.close();
                this.$refs.trigger.focus();
            },
        }"
        x-modelable="selectedValue"
        {{ $attributes->whereStartsWith('wire:model') }}
        @click.outside="close()"
        @keydown.escape="if (open) { $event.stopPropagation(); close(); }"
    >
        @if ($name)
            <input type="hidden" name="{{ $name }}" :value="selectedValue ?? ''">
        @endif

        <button
            type="button"
            x-ref="trigger"
            @click="open ? close() : openDropdown()"
            @keydown.arrow-down.prevent="openDropdown()"
            :aria-expanded="open"
            aria-haspopup="listbox"
            class="p-2 w-full border rounded-md text-left flex items-center justify-between bg-white dark:bg-zinc-900 text-black dark:text-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none cursor-pointer"
        >
            <span x-text="selectedLabel" class="truncate text-sm"></span>
            <x-lucide-chevrons-up-down class="size-4 shrink-0 ml-2 text-gray-400" />
        </button>

        <div
            x-show="open"
            x-anchor.bottom-start.fixed="$refs.trigger"
            x-transition:enter="transition ease-out duration-100"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            :style="{ width: panelWidth + 'px' }"
            @resize.window="if (open) panelWidth = $refs.trigger.offsetWidth"
            class="z-200 bg-white dark:bg-zinc-800 border rounded-md shadow-lg"
            style="display: none;"
        >
            <div class="p-2 border-b">
                <input
                    x-model="search"
                    x-ref="searchInput"
                    @input="activeIndex = 0"
                    @keydown.arrow-down.prevent="move(1)"
                    @keydown.arrow-up.prevent="move(-1)"
                    @keydown.enter.prevent="select(filteredOptions[activeIndex])"
                    @keydown.tab="close()"
                    type="text"
                    placeholder="{{ $placeholder }}"
                    class="w-full p-2 border rounded text-sm dark:bg-zinc-900 dark:text-white outline-none focus:border-blue-500"
                >
            </div>
            <div x-ref="list" role="listbox" class="max-h-48 overflow-y-auto">
                <template x-for="(option, index) in filteredOptions" :key="option.value">
                    <div
                        role="option"
                        :aria-selected="option.value === String(selectedValue ?? '')"
                        @click="select(option)"
                        @mouseenter="activeIndex = index"
                        :class="{ 'bg-gray-100 dark:bg-zinc-700': index === activeIndex }"
                        class="px-3 py-2 cursor-pointer text-sm text-black dark:text-white"
                        x-text="option.label"
                    ></div>
                </template>
                <div x-show="filteredOptions.length === 0" class="px-3 py-2 text-sm text-gray-500 dark:text-gray-400">
                    {{ __('ui::ui.no_results') }}
                </div>
            </div>
        </div>
    </div>
</x-ui::field>
