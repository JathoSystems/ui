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
])

{{--
    <x-ui::media src="/storage/a.jpg" :width="1600" :height="900" aspect="16:9" />
    <x-ui::media type="video" src="/storage/a.mp4" :autoplay="true" />
    <x-ui::media youtube="dQw4w9WgXcQ" />

    aspect: auto | square | 4:3 | 3:4 | 16:9 | 9:16 | 21:9
--}}

@php
    $type ??= $youtube ? 'youtube' : 'image';

    $aspectClass = match ($type === 'youtube' && $aspect === 'auto' ? '16:9' : $aspect) {
        'square' => 'aspect-square',
        '4:3' => 'aspect-4/3',
        '3:4' => 'aspect-3/4',
        '16:9' => 'aspect-video',
        '9:16' => 'aspect-9/16',
        '21:9' => 'aspect-21/9',
        default => null,
    };

    // Without a preset, reserve the media's own ratio so the layout doesn't jump while loading.
    $ratioStyle = ! $aspectClass && $width && $height ? "aspect-ratio: {$width} / {$height};" : null;

    $fillClasses = $aspectClass || $ratioStyle
        ? 'absolute inset-0 size-full '.($fit === 'contain' ? 'object-contain' : 'object-cover')
        : 'w-full';
@endphp

<div {{ $attributes->class(['relative overflow-hidden rounded-md bg-gray-100 dark:bg-zinc-900', $aspectClass]) }}
    @if ($ratioStyle) style="{{ $ratioStyle }}" @endif>
    @switch($type)
        @case('video')
            <video
                src="{{ $src }}"
                @if ($poster) poster="{{ $poster }}" @endif
                @if ($width) width="{{ $width }}" @endif
                @if ($height) height="{{ $height }}" @endif
                @if ($autoplay) autoplay muted loop playsinline @elseif ($controls) controls @endif
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
                        class="size-full"
                    ></iframe>
                </template>
                <button type="button" x-show="! playing" @click="playing = true"
                    aria-label="{{ __('ui::ui.play_video') }}{{ $alt ? ': '.$alt : '' }}"
                    class="group absolute inset-0 flex items-center justify-center cursor-pointer">
                    <img src="{{ $poster ?? "https://i.ytimg.com/vi/{$youtube}/hqdefault.jpg" }}" alt="" loading="lazy" decoding="async"
                        class="absolute inset-0 size-full object-cover">
                    <span class="relative flex items-center justify-center size-16 rounded-full bg-black/70 text-white transition-transform group-hover:scale-110">
                        <x-lucide-play class="size-7 translate-x-0.5" />
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
</div>
