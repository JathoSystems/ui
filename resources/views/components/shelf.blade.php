@props(['title' => '', 'customShelf' => false])

<div {{ $attributes->class('border-y transition-[size]') }}>
    <div class="max-w-6xl mx-auto w-full p-4 relative"
        x-data="{
            atEnd: true,
            observer: null,
            init() {
                const scroller = this.$el.querySelector('.overflow-x-auto');
                if (! scroller) return;
                const check = () => this.atEnd = scroller.scrollLeft + scroller.clientWidth >= scroller.scrollWidth - 1;
                scroller.addEventListener('scroll', check, { passive: true });
                this.observer = new ResizeObserver(check);
                this.observer.observe(scroller);
                Array.from(scroller.children).forEach(child => this.observer.observe(child));
                check();
            },
            destroy() {
                this.observer?.disconnect();
            },
        }">
        <div x-show="!atEnd"
            class="pointer-events-none absolute top-0 right-4 bottom-0 w-6 bg-linear-to-l from-gray-100 dark:from-zinc-900 z-10"></div>
        @if ($title)
            <h2 class="text-xl font-semibold mb-4">{{ $title }}</h2>
        @endif
        @if (! $customShelf)
            <div class="flex flex-row gap-4 overflow-x-auto overflow-y-hidden">
                {{ $slot }}
            </div>
        @else
            {{ $slot }}
        @endif
    </div>
</div>
