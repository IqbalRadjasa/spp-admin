@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation"
        class="flex flex-col sm:flex-row items-center justify-between gap-4 py-4">

        {{-- Left Side: Items Summary --}}
        <div class="text-xs text-stone-600 font-medium">
            @if ($paginator->firstItem())
                Showing
                <span class="font-semibold text-stone-900">{{ $paginator->firstItem() }}</span>
                to
                <span class="font-semibold text-stone-900">{{ $paginator->lastItem() }}</span>
                of
                <span class="font-semibold text-stone-900">{{ $paginator->total() }}</span>
                results
            @else
                {{ $paginator->count() }} results
            @endif
        </div>

        {{-- Right Side: Pagination Controls --}}
        <div class="flex items-center gap-2">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span
                    class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-stone-400 bg-stone-100 rounded-lg cursor-not-allowed border border-stone-200/60 select-none">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Previous
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')"
                    class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-stone-700 bg-white border border-stone-200 rounded-lg hover:bg-[#FCECD8]/50 hover:text-stone-900 transition-colors shadow-xs">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Previous
                </a>
            @endif

            {{-- Page Numbers --}}
            <div class="hidden md:flex items-center gap-1 text-xs font-medium">
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <span class="px-2 py-1 text-stone-400 select-none">{{ $element }}</span>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span aria-current="page"
                                    class="w-8 h-8 inline-flex items-center justify-center font-bold text-white bg-[#597928] rounded-lg shadow-xs select-none">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}"
                                    class="w-8 h-8 inline-flex items-center justify-center text-stone-600 hover:bg-[#FCECD8]/60 hover:text-stone-900 rounded-lg transition-colors">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </div>

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')"
                    class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-stone-700 bg-white border border-stone-200 rounded-lg hover:bg-[#FCECD8]/50 hover:text-stone-900 transition-colors shadow-xs">
                    Next
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            @else
                <span
                    class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-stone-400 bg-stone-100 rounded-lg cursor-not-allowed border border-stone-200/60 select-none">
                    Next
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </span>
            @endif
        </div>

    </nav>
@endif
