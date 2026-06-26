@extends('layouts.app')

@section('content')
    <section class="bg-[linear-gradient(180deg,#f7fbf8_0%,#ffffff_24%,#f4f7f3_100%)] py-10 sm:py-12 lg:py-16">
        <div class="mx-auto max-w-7xl px-4 md:px-8">
            <div class="mb-8 rounded-[2rem] border border-[#D9E6DE] bg-[#F7FBF8] p-6 shadow-[0_22px_55px_-35px_rgba(28,50,45,0.35)] sm:p-8">
                <div class="max-w-3xl space-y-4">
                    <span class="inline-flex rounded-full border border-[#C9D8D0] bg-white px-4 py-1 text-xs font-semibold uppercase tracking-[0.24em] text-[#4B655D]">
                        Informasi Resmi
                    </span>
                    <div class="space-y-3">
                        <h1 class="text-3xl font-semibold tracking-tight text-[#183530] sm:text-4xl">
                            Pengumuman Pondok
                        </h1>
                        <p class="max-w-2xl text-sm leading-7 text-[#5C746C] sm:text-base">
                            Ikuti informasi terbaru seputar agenda, pemberitahuan penting, dan pembaruan kegiatan santri
                            dalam satu halaman yang lebih rapi dan mudah dipantau.
                        </p>
                    </div>
                </div>
            </div>

            <div class="grid gap-8 lg:grid-cols-3 xl:gap-12">
                <div class="lg:col-span-2">
                    <div class="rounded-[2rem] border border-[#D9E6DE] bg-[#F7FBF8] p-5 shadow-[0_24px_60px_-35px_rgba(28,50,45,0.35)] sm:p-8">
                        <div class="mb-6 flex flex-col gap-3 border-b border-[#D4E2DA] pb-6 sm:flex-row sm:items-end sm:justify-between">
                            <div>
                                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-[#5E7A71]">Daftar Pengumuman</p>
                                <h2 class="mt-2 text-2xl font-semibold text-[#183530]">Update Terbaru</h2>
                            </div>
                            <p class="text-sm text-[#647A72]">
                                Menampilkan {{ $announcements->firstItem() ?? 0 }}-{{ $announcements->lastItem() ?? 0 }}
                                dari {{ $announcements->total() }} pengumuman
                            </p>
                        </div>

                        <div class="space-y-6">
                            @forelse ($announcements as $announcement)
                                <article class="rounded-[1.75rem] border border-[#D8E4DD] bg-white p-5 shadow-[0_20px_45px_-35px_rgba(28,50,45,0.42)] sm:p-6">
                                    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                                        <div class="space-y-3">
                                            <span class="inline-flex rounded-full bg-[#EDF4EF] px-3 py-1 text-xs font-semibold uppercase tracking-[0.16em] text-[#4F6B62]">
                                                Pengumuman
                                            </span>
                                            <h2 class="text-xl font-semibold leading-tight text-[#183530] sm:text-2xl">
                                                {{ $announcement->title }}
                                            </h2>
                                        </div>
                                        @if ($announcement->date)
                                            <p class="inline-flex items-center gap-2 rounded-full border border-[#D6E2DB] bg-[#F7FBF8] px-3 py-1.5 text-sm font-medium text-[#35514A]">
                                                <svg class="h-4 w-4 text-[#4F6B62]" viewBox="0 0 20 20"
                                                    fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd"
                                                        d="M6 2a1 1 0 011 1v1h6V3a1 1 0 112 0v1h1a2 2 0 012 2v9a2 2 0 01-2 2H4a2 2 0 01-2-2V6a2 2 0 012-2h1V3a1 1 0 112 0v1zm-2 5v7a1 1 0 001 1h10a1 1 0 001-1V7H4z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                <span>{{ \Illuminate\Support\Carbon::parse($announcement->date)->format('d F Y') }}</span>
                                            </p>
                                        @endif
                                    </div>

                                    <div class="mt-5 space-y-4 text-sm leading-7 text-[#5D736B] sm:text-[15px]">

                                        @if (!empty($announcement->image_url))
                                            <div class="overflow-hidden rounded-[1.5rem] border border-[#D5E2DA] bg-[#F7FBF8] p-2">
                                                <img src="{{ asset('storage/' . $announcement->image_url) }}" alt="{{ $announcement->title }}"
                                                    class="max-h-[24rem] w-full rounded-[1.1rem] object-cover" />
                                            </div>
                                        @endif

                                        @if (!empty($announcement->content))
                                            <div class="prose prose-sm max-w-none prose-p:text-[#5D736B] prose-headings:text-[#183530] prose-strong:text-[#183530]">
                                                {!! str($announcement->content)->sanitizeHtml() !!}
                                            </div>
                                        @endif
                                    </div>
                                </article>
                            @empty
                                <div class="rounded-[1.75rem] border border-dashed border-[#C9D8D0] bg-white px-6 py-10 text-center shadow-[0_18px_44px_-34px_rgba(28,50,45,0.28)]">
                                    <p class="text-sm text-[#60766E]">Belum ada pengumuman yang tersedia.</p>
                                </div>
                            @endforelse

                            @if ($announcements->hasPages())
                                <div class="border-t border-[#D4E2DA] pt-5 text-[#35514A]">
                                    {{ $announcements->onEachSide(1)->links('partials.pagination.pengumuman') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <aside class="space-y-6 lg:col-span-1">
                    <div class="rounded-[1.75rem] border border-[#D9E6DE] bg-white p-6 shadow-[0_20px_48px_-36px_rgba(28,50,45,0.35)]">
                        <header class="flex items-center justify-between">
                            <div>
                                <p class="text-sm uppercase tracking-[0.16em] text-[#60766E]">Kalender</p>
                                <h3 class="text-lg font-semibold text-[#183530]">
                                    {{ $calendarFirstOfMonth->format('F Y') }}
                                </h3>
                            </div>
                            <div class="flex items-center gap-2 text-[#8AA198]">
                                <button type="button" class="rounded-full border border-[#D6E2DB] p-1.5 transition hover:bg-[#F2F7F4]">
                                    <span class="sr-only">Bulan sebelumnya</span>
                                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                            d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <button type="button" class="rounded-full border border-[#D6E2DB] p-1.5 transition hover:bg-[#F2F7F4]">
                                    <span class="sr-only">Bulan berikutnya</span>
                                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                        </header>

                        <div class="mt-6">
                            <div class="grid grid-cols-7 gap-2 text-center text-xs font-semibold uppercase tracking-[0.12em] text-[#71867E]">
                                <span>Sen</span>
                                <span>Sel</span>
                                <span>Rab</span>
                                <span>Kam</span>
                                <span>Jum</span>
                                <span>Sab</span>
                                <span>Min</span>
                            </div>
                            <div class="mt-3 grid grid-cols-7 gap-2 text-sm">
                                @foreach ($calendarWeeks as $week)
                                    @foreach ($week as $index => $day)
                                        @php
                                            $isHighlighted = $day && in_array($day, $highlightedDays, true);
                                        @endphp
                                        <div
                                            class="{{ $day ? 'flex h-12 w-12 items-center justify-center rounded-xl border border-transparent mx-auto' : 'flex h-12 w-12 items-center justify-center mx-auto' }} {{ $isHighlighted ? 'bg-[#334B49] font-semibold text-white shadow-sm' : 'text-[#4E655D]' }}">
                                            {{ $day ?? '' }}
                                        </div>
                                    @endforeach
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="rounded-[1.75rem] border border-[#C9D8D0] bg-white p-6 shadow-[0_20px_48px_-36px_rgba(28,50,45,0.35)]">
                        <div class="flex items-start gap-3">
                            <div class="rounded-full bg-[#EDF4EF] p-2 text-[#35514A]">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M12 2a10 10 0 100 20 10 10 0 000-20zm.75 14.5h-1.5v-1.5h1.5v1.5zm0-3h-1.5v-6h1.5v6z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-[#183530]">Ada pertanyaan? Hubungi admin:</p>
                                <p class="mt-2 flex items-center gap-2 text-sm text-[#60766E]">
                                    <svg class="h-4 w-4 text-[#4F6B62]" viewBox="0 0 20 20" fill="currentColor"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M2.003 5.884l2-3.5A1 1 0 014.894 2h2.212a1 1 0 01.948.684l1.105 3.316a1 1 0 01-.502 1.21l-1.518.76a11.037 11.037 0 005.177 5.177l.76-1.518a1 1 0 011.21-.502l3.316 1.105A1 1 0 0118 12.894v2.212a1 1 0 01-.384.782l-3.5 2a1 1 0 01-1.016.054A17.944 17.944 0 012.059 6.9a1 1 0 01-.056-1.016z" />
                                    </svg>
                                    08xxxxxxxx (WhatsApp)
                                </p>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </section>
@endsection
