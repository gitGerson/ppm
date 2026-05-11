@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex justify-center">
        <div class="inline-flex items-center overflow-hidden rounded-2xl border border-[#31414D] bg-[#1F2B3B] shadow-[0_18px_40px_-28px_rgba(18,27,40,0.72)]">
            @if ($paginator->onFirstPage())
                <span class="flex h-12 w-12 items-center justify-center border-r border-[#415065] text-[#7F8A9A]">
                    <span class="sr-only">{{ __('Previous') }}</span>
                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                            clip-rule="evenodd" />
                    </svg>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}"
                    class="flex h-12 w-12 items-center justify-center border-r border-[#415065] text-[#F6FAFF] transition hover:bg-[#273446]"
                    rel="prev">
                    <span class="sr-only">{{ __('Previous') }}</span>
                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                            clip-rule="evenodd" />
                    </svg>
                </a>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="flex h-12 min-w-12 items-center justify-center border-r border-[#415065] px-4 text-sm font-semibold text-[#9DA8B8]">
                        {{ $element }}
                    </span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page"
                                class="flex h-12 min-w-12 items-center justify-center border-r border-[#415065] bg-[#3B4A60] px-4 text-sm font-semibold text-white">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}"
                                class="flex h-12 min-w-12 items-center justify-center border-r border-[#415065] px-4 text-sm font-semibold text-[#EAF1F8] transition hover:bg-[#273446] hover:text-white"
                                aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}"
                    class="flex h-12 w-12 items-center justify-center text-[#F6FAFF] transition hover:bg-[#273446]"
                    rel="next">
                    <span class="sr-only">{{ __('Next') }}</span>
                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                            clip-rule="evenodd" />
                    </svg>
                </a>
            @else
                <span class="flex h-12 w-12 items-center justify-center text-[#7F8A9A]">
                    <span class="sr-only">{{ __('Next') }}</span>
                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                            clip-rule="evenodd" />
                    </svg>
                </span>
            @endif
        </div>
    </nav>
@endif
