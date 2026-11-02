@props([
    'type' => null,
    'src' => null,
    'youtube' => null,
    'poster' => null,
    'alt' => '',
    'width' => null,
    'height' => null,
    'aspect' => 'auto',
    'fit' => 'cover',
    'autoplay' => false,
    'controls' => true,
    'rounded' => true,
])

{{--
    Renders a single image, video or YouTube embed with a preset aspect ratio.

    <x-ui::media src="/storage/a.jpg" alt="..." :width="1600" :height="900" aspect="16:9" />
    <x-ui::media type="video" src="/storage/a.mp4" :autoplay="true" />
    <x-ui::media youtube="dQw4w9WgXcQ" />

    aspect: auto | square | 4:3 | 3:4 | 16:9 | 9:16 | 21:9
    "auto" keeps the media's own ratio (from width/height when given, so the layout does not jump).
--}}

@php
    $type ??= $youtube ? 'youtube' : 'image';

    if ($type === 'youtube' && $aspect === 'auto') {
        $aspect = '16:9';
    }

    $aspectClass = match ($aspect) {
        'square' => 'aspect-square',
        '4:3' => 'aspect-4/3',
        '3:4' => 'aspect-3/4',
        '16:9' => 'aspect-video',
        '9:16' => 'aspect-9/16',
        '21:9' => 'aspect-21/9',
        default => null,
    };

    $intrinsicRatio = ! $aspectClass && $width && $height ? "aspect-ratio: {$width} / {$height};" : null;

    $fitClass = $fit === 'contain' ? 'object-contain' : 'object-cover';
    $fillClasses = $aspectClass || $intrinsicRatio ? "absolute inset-0 size-full {$fitClass}" : "block w-full h-auto";
@endphp

<div {{ $attributes->class([
    'relative overflow-hidden bg-gray-100 dark:bg-zinc-900',
    'rounded-md' => $rounded,
    $aspectClass,
]) }} @if ($intrinsicRatio) style="{{ $intrinsicRatio }}" @endif>
    @switch($type)
        @case('video')
            <video
                src="{{ $src }}"
                @if ($poster) poster="{{ $poster }}" @endif
                @if ($width) width="{{ $width }}" @endif
                @if ($height) height="{{ $height }}" @endif
                @if ($autoplay) autoplay muted loop playsinline @endif
                @if ($controls && ! $autoplay) controls @endif
                preload="metadata"
                class="{{ $fillClasses }}"
            ></video>
            @break

        @case('youtube')
            <div x-data="{ playing: false }" class="absolute inset-0">
                <template x-if="playing">
                    <iframe
                        src="https://www.youtube-nocookie.com/embed/{{ $youtube }}?autoplay=1&rel=0"
                        title="{{ $alt ?: 'YouTube' }}"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen
                        class="absolute inset-0 size-full border-0"
                    ></iframe>
                </template>
                <button type="button" x-show="! playing" @click="playing = true"
                    aria-label="{{ __('ui::ui.play_video') }}{{ $alt ? ': '.$alt : '' }}"
                    class="group absolute inset-0 size-full cursor-pointer">
                    <img src="{{ $poster ?? "https://i.ytimg.com/vi/{$youtube}/hqdefault.jpg" }}" alt="" loading="lazy" decoding="async"
                        class="absolute inset-0 size-full object-cover">
                    <span class="absolute inset-0 flex items-center justify-center bg-black/20 transition-colors group-hover:bg-black/40">
                        <span class="flex items-center justify-center size-16 rounded-full bg-black/70 text-white transition-transform group-hover:scale-110">
                            <x-lucide-play class="size-7 translate-x-0.5" />
                        </span>
                    </span>
                </button>
            </div>
            @break

        @default
            <img
                src="{{ $src }}"
                alt="{{ $alt }}"
                @if ($width) width="{{ $width }}" @endif
                @if ($height) height="{{ $height }}" @endif
                loading="lazy"
                decoding="async"
                class="{{ $fillClasses }}"
            >
    @endswitch

    {{ $slot }}
</div>
