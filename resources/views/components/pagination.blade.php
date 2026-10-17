@php
    $pageName = $paginator->getPageName();
    $linkClasses = 'btn-base bg-gray-100 dark:bg-zinc-900 hover:bg-gray-200 dark:hover:bg-zinc-950';
    $disabledClasses = 'btn-base bg-gray-100 dark:bg-zinc-900 opacity-50 cursor-not-allowed';
@endphp

<div>
    @if ($paginator->hasPages())
        <nav role="navigation" aria-label="Pagination Navigation" class="mt-4">
            <div class="flex justify-between flex-1 sm:hidden">
                @if ($paginator->onFirstPage())
                    <span class="{{ $disabledClasses }}" aria-disabled="true">
                        <x-lucide-arrow-left class="size-5" /> {{ __('ui::ui.previous') }}
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="{{ $linkClasses }}"
                        wire:click.prevent="previousPage('{{ $pageName }}')">
                        <x-lucide-arrow-left class="size-5" /> {{ __('ui::ui.previous') }}
                    </a>
                @endif

                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="{{ $linkClasses }}"
                        wire:click.prevent="nextPage('{{ $pageName }}')">
                        {{ __('ui::ui.next') }} <x-lucide-arrow-right class="size-5" />
                    </a>
                @else
                    <span class="{{ $disabledClasses }}" aria-disabled="true">
                        {{ __('ui::ui.next') }} <x-lucide-arrow-right class="size-5" />
                    </span>
                @endif
            </div>

            <div class="sm:flex justify-between items-center hidden">
                <div class="text-md leading-5">
                    {{ __('ui::ui.showing') }} <b>{{ $paginator->firstItem() }}</b> {{ __('ui::ui.to') }} <b>{{ $paginator->lastItem() }}</b>
                    {{ __('ui::ui.of') }} <b>{{ $paginator->total() }}</b> {{ __('ui::ui.results') }}
                </div>

                <div class="flex">
                    @if ($paginator->onFirstPage())
                        <span class="{{ $disabledClasses }} rounded-r-none" aria-disabled="true" aria-label="{{ __('ui::ui.previous') }}">
                            <x-lucide-arrow-left class="size-5" />
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="{{ __('ui::ui.previous') }}"
                            class="{{ $linkClasses }} rounded-r-none"
                            wire:click.prevent="previousPage('{{ $pageName }}')">
                            <x-lucide-arrow-left class="size-5" />
                        </a>
                    @endif

                    @foreach ($elements as $element)
                        @if (is_string($element))
                            <span class="{{ $disabledClasses }} rounded-none cursor-default" aria-disabled="true">{{ $element }}</span>
                        @endif

                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page"
                                        class="btn-base rounded-none font-bold bg-blue-500 dark:bg-blue-700 text-white cursor-default"
                                        wire:key="paginator-{{ $pageName }}-page-{{ $page }}">{{ $page }}</span>
                                @else
                                    <a href="{{ $url }}" class="{{ $linkClasses }} rounded-none"
                                        wire:key="paginator-{{ $pageName }}-page-{{ $page }}"
                                        wire:click.prevent="gotoPage({{ $page }}, '{{ $pageName }}')">{{ $page }}</a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="{{ __('ui::ui.next') }}"
                            class="{{ $linkClasses }} rounded-l-none"
                            wire:click.prevent="nextPage('{{ $pageName }}')">
                            <x-lucide-arrow-right class="size-5" />
                        </a>
                    @else
                        <span class="{{ $disabledClasses }} rounded-l-none" aria-disabled="true" aria-label="{{ __('ui::ui.next') }}">
                            <x-lucide-arrow-right class="size-5" />
                        </span>
                    @endif
                </div>
            </div>
        </nav>
    @endif
</div>
