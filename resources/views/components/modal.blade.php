@props([
    'title' => '',
    'trigger' => null,
    'triggerClass' => '',
    'closable' => true,
    'openEvent' => null,
    'closeEvent' => null,
])

<div x-data="{
    open: false,
    openModal() {
        this.open = true;
        this.$dispatch('modal:opened');
    },
    closeModal() {
        this.open = false;
    }
}" x-on:close-modal.stop="closeModal()"
    @if ($openEvent) x-on:{{ $openEvent }}.window="openModal()" @endif
    @if ($closeEvent) x-on:{{ $closeEvent }}.window="closeModal()" @endif>

    @if ($trigger)
        <div @click="openModal()" class="cursor-pointer {{ $triggerClass }}">
            {{ $trigger }}
        </div>
    @endif

    <div x-show="open"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @keydown.escape.stop="closeModal()"
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         style="display: none;">

        <div @click="closeModal()" class="absolute inset-0 bg-black/80"></div>

        <div x-show="open"
             x-trap.noscroll="open"
             role="dialog"
             aria-modal="true"
             @if ($title) aria-label="{{ $title }}" @endif
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             {{ $attributes->merge(['class' => 'relative w-full max-h-[80vh] overflow-auto max-w-lg bg-white dark:bg-zinc-800 rounded-lg shadow-xl border']) }}
             @click.stop>

            @isset($header)
                {{ $header }}
            @elseif ($title || $closable)
                <div class="flex items-center justify-between p-4 border-b">
                    @if ($title)
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $title }}</h3>
                    @endif

                    @if ($closable)
                        <button type="button" @click="closeModal()" aria-label="{{ __('ui::ui.close') }}"
                                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors p-1 rounded-md hover:bg-gray-100 dark:hover:bg-zinc-700 cursor-pointer">
                            <x-lucide-x class="size-5" />
                        </button>
                    @endif
                </div>
            @endisset

            <div class="p-4">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
