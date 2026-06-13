@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}">

        <div class="flex gap-2 items-center justify-between sm:hidden">

            @if ($paginator->onFirstPage())
                <span class="inline-flex items-center px-4 py-2 text-sm font-bold text-slate-400 bg-slate-50 border border-slate-200 cursor-not-allowed leading-5 rounded-lg shadow-sm">
                    {!! __('pagination.previous') !!}
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center px-4 py-2 text-sm font-bold text-slate-700 bg-white border border-slate-300 leading-5 rounded-lg hover:text-blue-600 hover:border-blue-500 hover:bg-blue-50/30 focus:outline-none active:bg-slate-100 transition ease-in-out duration-150 shadow-sm">
                    {!! __('pagination.previous') !!}
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center px-4 py-2 text-sm font-bold text-slate-700 bg-white border border-slate-300 leading-5 rounded-lg hover:text-blue-600 hover:border-blue-500 hover:bg-blue-50/30 focus:outline-none active:bg-slate-100 transition ease-in-out duration-150 shadow-sm">
                    {!! __('pagination.next') !!}
                </a>
            @else
                <span class="inline-flex items-center px-4 py-2 text-sm font-bold text-slate-400 bg-slate-50 border border-slate-200 cursor-not-allowed leading-5 rounded-lg shadow-sm">
                    {!! __('pagination.next') !!}
                </span>
            @endif

        </div>

        <div class="hidden sm:flex-1 sm:flex sm:gap-4 sm:items-center sm:justify-between">

            <div>
                <p class="text-sm text-slate-600 leading-5 font-medium">
                    {!! __('Menampilkan') !!}
                    @if ($paginator->firstItem())
                        <span class="font-bold text-slate-900 bg-slate-100 px-2 py-0.5 rounded">{{ $paginator->firstItem() }}</span>
                        {!! __('sampai') !!}
                        <span class="font-bold text-slate-900 bg-slate-100 px-2 py-0.5 rounded">{{ $paginator->lastItem() }}</span>
                    @else
                        <span class="font-bold text-slate-900 bg-slate-100 px-2 py-0.5 rounded">{{ $paginator->count() }}</span>
                    @endif
                    {!! __('dari') !!}
                    <span class="font-bold text-slate-900 bg-slate-100 px-2 py-0.5 rounded">{{ $paginator->total() }}</span>
                    {!! __('data') !!}
                </p>
            </div>

            <div>
                <span class="inline-flex gap-1.5 rtl:flex-row-reverse">

                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                            <span class="inline-flex items-center px-3 py-2 text-sm font-bold text-slate-400 bg-slate-50 border border-slate-200 cursor-not-allowed rounded-lg leading-5 shadow-sm" aria-hidden="true">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center px-3 py-2 text-sm font-bold text-slate-700 bg-white border border-slate-300 rounded-lg leading-5 hover:text-blue-600 hover:border-blue-500 hover:bg-blue-50/30 focus:outline-none transition ease-in-out duration-150 shadow-sm" aria-label="{{ __('pagination.previous') }}">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <span aria-disabled="true">
                                <span class="inline-flex items-center px-4 py-2 text-sm font-bold text-slate-400 bg-slate-50 border border-slate-200 cursor-default rounded-lg leading-5 shadow-sm">{{ $element }}</span>
                            </span>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page">
                                        <span class="inline-flex items-center px-4 py-2 text-sm font-bold text-white bg-blue-600 border border-blue-600 cursor-default rounded-lg leading-5 shadow-md z-10">{{ $page }}</span>
                                    </span>
                                @else
                                    <a href="{{ $url }}" class="inline-flex items-center px-4 py-2 text-sm font-semibold text-slate-700 bg-white border border-slate-300 rounded-lg leading-5 hover:text-blue-600 hover:border-blue-500 hover:bg-blue-50/30 focus:outline-none transition ease-in-out duration-150 shadow-sm" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center px-3 py-2 text-sm font-bold text-slate-700 bg-white border border-slate-300 rounded-lg leading-5 hover:text-blue-600 hover:border-blue-500 hover:bg-blue-50/30 focus:outline-none transition ease-in-out duration-150 shadow-sm" aria-label="{{ __('pagination.next') }}">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    @else
                        <span aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                            <span class="inline-flex items-center px-3 py-2 text-sm font-bold text-slate-400 bg-slate-50 border border-slate-200 cursor-not-allowed rounded-lg leading-5 shadow-sm" aria-hidden="true">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        </span>
                    @endif
                </span>
            </div>
        </div>
    </nav>
@endif
