@props([
    'perView' => 1,
    'gap' => 'md',
    'nav' => 'dots',
    'autoplay' => 0,
    'label' => null,
])

{{--
    <x-ui::carousel nav="thumbnails" :autoplay="5000">
        <x-ui::carousel.slide thumbnail="/storage/a.jpg">...</x-ui::carousel.slide>
    </x-ui::carousel>

    per-view: 1 | 2 | 3   gap: none | sm | md | lg   nav: dots | thumbnails | none
    autoplay: interval in ms, 0 is off. Pauses on hover, focus, a hidden tab and reduced motion.
--}}

@php
    $trackGap = match ($gap) {
        'none' => '',
        'sm' => '-mx-1',
        'lg' => '-mx-4',
        default => '-mx-2',
    };
@endphp

<div
    x-data="{
        index: 0,
        slides: [],
        paused: false,
        timer: null,
        init() {
            this.refresh()
            new MutationObserver(() => this.refresh()).observe(this.$refs.track, { childList: true })

            const interval = @js((int) $autoplay);
            if (interval && ! matchMedia('(prefers-reduced-motion: reduce)').matches) {
                this.timer = setInterval(() => {
                    if (! this.paused && ! document.hidden) this.goTo(this.index + 1)
                }, interval)
            }
        },
        destroy() {
            clearInterval(this.timer)
        },
        refresh() {
            this.slides = [...this.$refs.track.querySelectorAll(':scope > [data-carousel-slide]')]
        },
        sync() {
            const left = this.$refs.track.scrollLeft
            this.index = this.slides.reduce((closest, slide, i) =>
                Math.abs(slide.offsetLeft - left) < Math.abs(this.slides[closest].offsetLeft - left) ? i : closest, 0)
        },
        goTo(i) {
            if (this.slides.length < 2) return
            this.index = (i + this.slides.length) % this.slides.length
            this.$refs.track.scrollTo({ left: this.slides[this.index].offsetLeft, behavior: 'smooth' })
        },
    }"
    @mouseenter="paused = true"
    @mouseleave="paused = false"
    @focusin="paused = true"
    @focusout="paused = false"
    @keydown.arrow-right.prevent="goTo(index + 1)"
    @keydown.arrow-left.prevent="goTo(index - 1)"
    role="region"
    aria-roledescription="carousel"
    @if ($label) aria-label="{{ $label }}" @endif
    {{ $attributes }}
>
    <div class="relative overflow-hidden">
        <div x-ref="track" tabindex="0" @scroll.passive="sync()"
            class="relative flex overflow-x-auto snap-x snap-mandatory [scrollbar-width:none] {{ $trackGap }}">
            {{ $slot }}
        </div>

        <template x-if="slides.length > 1">
            <div>
                <button type="button" @click="goTo(index - 1)" aria-label="{{ __('ui::ui.previous') }}"
                    class="absolute left-2 top-1/2 -translate-y-1/2 flex items-center justify-center size-10 rounded-full bg-black/60 text-white hover:bg-black/80 transition-colors cursor-pointer">
                    <x-lucide-chevron-left class="size-5" />
                </button>
                <button type="button" @click="goTo(index + 1)" aria-label="{{ __('ui::ui.next') }}"
                    class="absolute right-2 top-1/2 -translate-y-1/2 flex items-center justify-center size-10 rounded-full bg-black/60 text-white hover:bg-black/80 transition-colors cursor-pointer">
                    <x-lucide-chevron-right class="size-5" />
                </button>
            </div>
        </template>
    </div>

    @if ($nav === 'dots')
        <div x-show="slides.length > 1" class="flex justify-center gap-2 mt-3">
            <template x-for="(slide, i) in slides" :key="i">
                <button type="button" @click="goTo(i)"
                    :aria-label="@js(__('ui::ui.go_to_slide')) + ' ' + (i + 1)"
                    :aria-current="index === i"
                    :class="index === i ? 'w-6' : 'w-2 opacity-30'"
                    class="h-2 rounded-full bg-current transition-all cursor-pointer"></button>
            </template>
        </div>
    @elseif ($nav === 'thumbnails')
        <div x-show="slides.length > 1" class="flex gap-2 mt-2 overflow-x-auto p-1">
            <template x-for="(slide, i) in slides" :key="i">
                <button type="button" @click="goTo(i)"
                    :aria-label="@js(__('ui::ui.go_to_slide')) + ' ' + (i + 1)"
                    :aria-current="index === i"
                    :class="index === i ? 'ring-2 ring-blue-500' : 'opacity-60 hover:opacity-100'"
                    class="relative shrink-0 w-20 aspect-video rounded overflow-hidden bg-gray-200 dark:bg-zinc-800 text-sm transition-opacity cursor-pointer">
                    <template x-if="slide.dataset.thumbnail">
                        <img :src="slide.dataset.thumbnail" alt="" loading="lazy" class="absolute inset-0 size-full object-cover">
                    </template>
                    <span x-show="! slide.dataset.thumbnail" x-text="i + 1"></span>
                </button>
            </template>
        </div>
    @endif
</div>
