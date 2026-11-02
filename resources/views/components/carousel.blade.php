@props([
    'perView' => 1,
    'gap' => 'md',
    'nav' => 'dots',
    'arrows' => true,
    'loop' => true,
    'autoplay' => 0,
    'label' => null,
])

{{--
    Scroll-snap carousel. Slides are <x-ui::carousel.slide> children.

    <x-ui::carousel per-view="1" nav="thumbnails" :autoplay="5000">
        <x-ui::carousel.slide thumbnail="/storage/a.jpg">...</x-ui::carousel.slide>
    </x-ui::carousel>

    per-view: 1 | 2 | 3   gap: none | sm | md | lg   nav: dots | thumbnails | none
    autoplay: interval in ms (0 = off). Pauses on hover, focus, hidden tab and reduced motion.
    Extra content (e.g. an editor placeholder) can be passed as further slides.
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
        loop: @js((bool) $loop),
        interval: @js((int) $autoplay),
        paused: false,
        timer: null,
        observer: null,
        mutations: null,
        init() {
            this.refresh();
            this.mutations = new MutationObserver(() => this.refresh());
            this.mutations.observe(this.$refs.track, { childList: true });
            this.$refs.track.addEventListener('scroll', () => this.syncIndex(), { passive: true });
            this.start();
        },
        destroy() {
            this.stop();
            this.mutations?.disconnect();
        },
        refresh() {
            this.slides = Array.from(this.$refs.track.children)
                .filter(el => el.hasAttribute('data-carousel-slide'))
                .map((el, i) => ({ i, el, thumb: el.dataset.thumbnail || null }));
            this.index = Math.min(this.index, Math.max(this.slides.length - 1, 0));
        },
        syncIndex() {
            const track = this.$refs.track;
            const left = track.scrollLeft;
            let closest = 0;
            this.slides.forEach((s, i) => {
                if (Math.abs(s.el.offsetLeft - left) < Math.abs(this.slides[closest].el.offsetLeft - left)) closest = i;
            });
            this.index = closest;
        },
        goTo(i) {
            if (! this.slides.length) return;
            const count = this.slides.length;
            i = this.loop ? (i + count) % count : Math.max(0, Math.min(i, count - 1));
            this.index = i;
            this.$refs.track.scrollTo({ left: this.slides[i].el.offsetLeft, behavior: 'smooth' });
        },
        next() { this.goTo(this.index + 1) },
        prev() { this.goTo(this.index - 1) },
        get atStart() { return ! this.loop && this.index === 0 },
        get atEnd() { return ! this.loop && this.index >= this.slides.length - 1 },
        start() {
            if (! this.interval || window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
            this.stop();
            this.timer = setInterval(() => {
                if (! this.paused && ! document.hidden && this.slides.length > 1) this.next();
            }, this.interval);
        },
        stop() {
            clearInterval(this.timer);
            this.timer = null;
        },
    }"
    @mouseenter="paused = true"
    @mouseleave="paused = false"
    @focusin="paused = true"
    @focusout="paused = false"
    @keydown.arrow-right.prevent="next()"
    @keydown.arrow-left.prevent="prev()"
    role="region"
    aria-roledescription="carousel"
    @if ($label) aria-label="{{ $label }}" @endif
    {{ $attributes->class('relative') }}
>
    <div class="relative overflow-hidden">
        <div x-ref="track" tabindex="0"
            class="relative flex overflow-x-auto snap-x snap-mandatory scroll-smooth [scrollbar-width:none] [&::-webkit-scrollbar]:hidden outline-none {{ $trackGap }}">
            {{ $slot }}
        </div>

        @if ($arrows)
            <button type="button" @click="prev()" x-show="slides.length > 1" :disabled="atStart"
                aria-label="{{ __('ui::ui.previous') }}"
                class="absolute left-2 top-1/2 -translate-y-1/2 flex items-center justify-center size-10 rounded-full bg-black/60 text-white hover:bg-black/80 disabled:opacity-0 transition cursor-pointer">
                <x-lucide-chevron-left class="size-5" />
            </button>
            <button type="button" @click="next()" x-show="slides.length > 1" :disabled="atEnd"
                aria-label="{{ __('ui::ui.next') }}"
                class="absolute right-2 top-1/2 -translate-y-1/2 flex items-center justify-center size-10 rounded-full bg-black/60 text-white hover:bg-black/80 disabled:opacity-0 transition cursor-pointer">
                <x-lucide-chevron-right class="size-5" />
            </button>
        @endif
    </div>

    @if ($nav === 'dots')
        <div x-show="slides.length > 1" class="flex justify-center gap-2 mt-3">
            <template x-for="slide in slides" :key="slide.i">
                <button type="button" @click="goTo(slide.i)"
                    :aria-label="@js(__('ui::ui.go_to_slide')) + ' ' + (slide.i + 1)"
                    :aria-current="index === slide.i ? 'true' : 'false'"
                    :class="index === slide.i ? 'bg-gray-800 dark:bg-white w-6' : 'bg-gray-300 dark:bg-zinc-600 w-2'"
                    class="h-2 rounded-full transition-all cursor-pointer"></button>
            </template>
        </div>
    @elseif ($nav === 'thumbnails')
        <div x-show="slides.length > 1" class="flex gap-2 mt-3 overflow-x-auto pb-1">
            <template x-for="slide in slides" :key="slide.i">
                <button type="button" @click="goTo(slide.i)"
                    :aria-label="@js(__('ui::ui.go_to_slide')) + ' ' + (slide.i + 1)"
                    :aria-current="index === slide.i ? 'true' : 'false'"
                    :class="index === slide.i ? 'ring-2 ring-blue-500 opacity-100' : 'opacity-60 hover:opacity-100'"
                    class="relative shrink-0 w-20 aspect-video rounded overflow-hidden bg-gray-200 dark:bg-zinc-800 transition cursor-pointer">
                    <template x-if="slide.thumb">
                        <img :src="slide.thumb" alt="" loading="lazy" class="absolute inset-0 size-full object-cover">
                    </template>
                    <span x-show="! slide.thumb" x-text="slide.i + 1" class="text-sm"></span>
                </button>
            </template>
        </div>
    @endif
</div>
