@props(['items' => []])

{{--
    Breadcrumb component
    Usage: <x-ui::breadcrumb :items="[
        ['label' => 'Home', 'href' => route('home')],
        ['label' => 'Races',  'href' => route('race.index')],
        ['label' => 'Current Page'],  no href = current page
    ]" />
--}}

<nav aria-label="Breadcrumb" class="mb-3">
    <ol class="flex flex-wrap items-center gap-1 text-sm" role="list">
        @foreach ($items as $index => $item)
            @php $isLast = $index === count($items) - 1; @endphp

            <li class="flex items-center gap-1">
                @if ($isLast)
                    <span aria-current="page" class="font-medium">{{ $item['label'] }}</span>
                @elseif (! empty($item['href']))
                    <a
                        href="{{ $item['href'] }}"
                        class="opacity-75 hover:opacity-100 transition-opacity duration-150 underline-offset-2 hover:underline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:rounded-sm"
                    >{{ $item['label'] }}</a>
                @else
                    <span class="opacity-75">{{ $item['label'] }}</span>
                @endif

                @unless ($isLast)
                    <span aria-hidden="true" class="opacity-75 select-none">
                        <x-lucide-chevron-right class="size-3.5 inline-block" />
                    </span>
                @endunless
            </li>
        @endforeach
    </ol>
</nav>
