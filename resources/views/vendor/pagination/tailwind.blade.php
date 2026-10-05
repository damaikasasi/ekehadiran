@if ($paginator->hasPages())
    @php
        $currentPage = $paginator->currentPage();
        $lastPage = $paginator->lastPage();
        
        // Tampilkan tepat 3 angka yang bergeser dinamis
        if ($lastPage <= 3) {
            $startPage = 1;
            $endPage = $lastPage;
        } else {
            if ($currentPage <= 2) {
                $startPage = 1;
                $endPage = 3;
            } elseif ($currentPage >= $lastPage - 1) {
                $startPage = $lastPage - 2;
                $endPage = $lastPage;
            } else {
                $startPage = $currentPage - 1;
                $endPage = $currentPage + 1;
            }
        }
    @endphp

    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-between">
        {{-- Mobile View (< sm) --}}
        <div class="flex justify-between flex-1 sm:hidden gap-2">
            @if ($paginator->onFirstPage())
                <span class="inline-flex items-center px-3.5 py-2 text-xs font-semibold text-gray-400 bg-gray-100 border border-gray-200 cursor-not-allowed rounded-xl select-none">
                    {!! __('pagination.previous') !!}
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center px-3.5 py-2 text-xs font-semibold text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition shadow-xs">
                    {!! __('pagination.previous') !!}
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center px-3.5 py-2 text-xs font-semibold text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition shadow-xs">
                    {!! __('pagination.next') !!}
                </a>
            @else
                <span class="inline-flex items-center px-3.5 py-2 text-xs font-semibold text-gray-400 bg-gray-100 border border-gray-200 cursor-not-allowed rounded-xl select-none">
                    {!! __('pagination.next') !!}
                </span>
            @endif
        </div>

        {{-- Desktop / Tablet View (>= sm) --}}
        <div class="hidden sm:flex sm:items-center sm:justify-end">
            <span class="inline-flex shadow-xs rounded-xl overflow-hidden border border-gray-200 bg-white">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                        <span class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-300 bg-gray-50 cursor-not-allowed" aria-hidden="true">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </span>
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-600 bg-white hover:bg-gray-50 hover:text-[#0b602b] transition" aria-label="{{ __('pagination.previous') }}">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </a>
                @endif

                {{-- Exactly 3 Sliding Number Links --}}
                @for ($page = $startPage; $page <= $endPage; $page++)
                    @if ($page == $currentPage)
                        <span aria-current="page">
                            <span class="inline-flex items-center px-3.5 py-2 text-sm font-bold text-white bg-[#0b602b] border-l border-gray-200 cursor-default">{{ $page }}</span>
                        </span>
                    @else
                        <a href="{{ $paginator->url($page) }}" class="inline-flex items-center px-3.5 py-2 text-sm font-medium text-gray-700 bg-white border-l border-gray-200 hover:bg-[#0b602b]/10 hover:text-[#0b602b] transition" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                            {{ $page }}
                        </a>
                    @endif
                @endfor

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-600 bg-white border-l border-gray-200 hover:bg-gray-50 hover:text-[#0b602b] transition" aria-label="{{ __('pagination.next') }}">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </a>
                @else
                    <span aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                        <span class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-300 bg-gray-50 border-l border-gray-200 cursor-not-allowed" aria-hidden="true">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </span>
                    </span>
                @endif
            </span>
        </div>
    </nav>
@endif
