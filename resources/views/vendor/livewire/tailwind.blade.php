@php
if (! isset($scrollTo)) {
    $scrollTo = 'body';
}

$scrollIntoViewJsSnippet = ($scrollTo !== false)
    ? <<<JS
       (\$el.closest('{$scrollTo}') || document.querySelector('{$scrollTo}')).scrollIntoView()
    JS
    : '';
@endphp

<div>
    @if ($paginator->hasPages())
        <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between border-t border-gray-100 pt-4">
            {{-- Mobile View --}}
            <div class="flex justify-between flex-1 sm:hidden">
                <span>
                    @if ($paginator->onFirstPage())
                        <span class="relative inline-flex items-center px-3 py-1.5 text-sm font-medium text-gray-400 bg-white border border-gray-200 cursor-default rounded-md">
                            Anterior
                        </span>
                    @else
                        <button type="button" wire:click="previousPage('{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" wire:loading.attr="disabled" class="relative inline-flex items-center px-3 py-1.5 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-md hover:bg-gray-50 transition-colors">
                            Anterior
                        </button>
                    @endif
                </span>

                <span>
                    @if ($paginator->hasMorePages())
                        <button type="button" wire:click="nextPage('{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" wire:loading.attr="disabled" class="relative inline-flex items-center px-3 py-1.5 ml-3 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-md hover:bg-gray-50 transition-colors">
                            Siguiente
                        </button>
                    @else
                        <span class="relative inline-flex items-center px-3 py-1.5 ml-3 text-sm font-medium text-gray-400 bg-white border border-gray-200 cursor-default rounded-md">
                            Siguiente
                        </span>
                    @endif
                </span>
            </div>

            {{-- Desktop View --}}
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-xs text-gray-500">
                        Mostrando <span class="font-medium text-gray-900">{{ $paginator->firstItem() }}</span> a <span class="font-medium text-gray-900">{{ $paginator->lastItem() }}</span> de <span class="font-medium text-gray-900">{{ $paginator->total() }}</span> resultados
                    </p>
                </div>

                <div>
                    <span class="relative z-0 inline-flex gap-1">
                        {{-- Previous Page Link --}}
                        @if ($paginator->onFirstPage())
                            <span class="relative inline-flex items-center px-2 py-1 text-sm font-medium text-gray-300 bg-white border border-gray-100 cursor-default rounded">
                                ←
                            </span>
                        @else
                            <button type="button" wire:click="previousPage('{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" class="relative inline-flex items-center px-2 py-1 text-sm font-medium text-gray-600 bg-white border border-gray-100 rounded hover:bg-gray-50 hover:text-gray-900 transition-colors">
                                ←
                            </button>
                        @endif

                        {{-- Pagination Elements --}}
                        @foreach ($elements as $element)
                            {{-- "Three Dots" Separator --}}
                            @if (is_string($element))
                                <span class="relative inline-flex items-center px-3 py-1 text-sm font-medium text-gray-400 bg-white border border-gray-100 cursor-default rounded">{{ $element }}</span>
                            @endif

                            {{-- Array Of Links --}}
                            @if (is_array($element))
                                @foreach ($element as $page => $url)
                                    <span wire:key="paginator-{{ $paginator->getPageName() }}-page{{ $page }}">
                                        @if ($page == $paginator->currentPage())
                                            <span class="relative inline-flex items-center px-3 py-1 text-sm font-medium text-white bg-gray-900 border border-gray-900 cursor-default rounded">
                                                {{ $page }}
                                            </span>
                                        @else
                                            <button type="button" wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" class="relative inline-flex items-center px-3 py-1 text-sm font-medium text-gray-600 bg-white border border-gray-100 rounded hover:bg-gray-50 hover:text-gray-900 transition-colors">
                                                {{ $page }}
                                            </button>
                                        @endif
                                    </span>
                                @endforeach
                            @endif
                        @endforeach

                        {{-- Next Page Link --}}
                        @if ($paginator->hasMorePages())
                            <button type="button" wire:click="nextPage('{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" class="relative inline-flex items-center px-2 py-1 text-sm font-medium text-gray-600 bg-white border border-gray-100 rounded hover:bg-gray-50 hover:text-gray-900 transition-colors">
                                →
                            </button>
                        @else
                            <span class="relative inline-flex items-center px-2 py-1 text-sm font-medium text-gray-300 bg-white border border-gray-100 cursor-default rounded">
                                →
                            </span>
                        @endif
                    </span>
                </div>
            </div>
        </nav>
    @endif
</div>
