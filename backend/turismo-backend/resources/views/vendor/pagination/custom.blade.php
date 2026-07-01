@if ($paginator->hasPages())
    <nav class="pagination" role="navigation" aria-label="Pagination Navigation">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="page-btn" style="opacity: 0.5; cursor: not-allowed;" aria-disabled="true">‹</span>
        @else
            <button type="button" class="page-btn" wire:click="previousPage('page')" wire:loading.attr="disabled" rel="prev">‹</button>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span class="page-btn" style="opacity: 0.5; cursor: not-allowed;" aria-disabled="true">{{ $element }}</span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="page-btn active" aria-current="page">{{ $page }}</span>
                    @else
                        <button type="button" class="page-btn" wire:click="gotoPage({{ $page }}, 'page')" wire:loading.attr="disabled">{{ $page }}</button>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <button type="button" class="page-btn" wire:click="nextPage('page')" wire:loading.attr="disabled" rel="next">›</button>
        @else
            <span class="page-btn" style="opacity: 0.5; cursor: not-allowed;" aria-disabled="true">›</span>
        @endif
    </nav>
@endif
