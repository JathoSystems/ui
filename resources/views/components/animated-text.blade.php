@props(['extraDelay' => 0])

@php
    $words = preg_split('/\s+/', trim(html_entity_decode(strip_tags((string) $slot), ENT_QUOTES)), -1, PREG_SPLIT_NO_EMPTY);
@endphp

<div {{ $attributes }}>
    @foreach ($words as $index => $word)
        <span class="inline-block animate-up" style="animation-delay: {{ 0.2 + $extraDelay + $index * 0.025 }}s;">{{ $word }}</span>
    @endforeach
</div>
