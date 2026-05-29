@extends('layouts.app')

@section('content')
    <section class="bg-[linear-gradient(180deg,#f7fbf8_0%,#ffffff_24%,#f4f7f3_100%)] py-10 sm:py-12 lg:py-16">
        <div class="mx-auto max-w-7xl px-4 md:px-8">
            <div class="mb-8 rounded-[2rem] border border-[#D9E6DE] bg-[#F7FBF8] p-6 shadow-[0_22px_55px_-35px_rgba(28,50,45,0.35)] sm:p-8">
                <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                    <div class="max-w-3xl space-y-4">
                        <span class="inline-flex rounded-full border border-[#C9D8D0] bg-white px-4 py-1 text-xs font-semibold uppercase tracking-[0.24em] text-[#4B655D]">
                            Kegiatan Belajar Mengajar
                        </span>
                        <div class="space-y-3">
                            <h1 class="text-3xl font-semibold tracking-tight text-[#183530] sm:text-4xl">
                                {{ $categoryName }}
                            </h1>
                            <p class="max-w-2xl text-sm leading-7 text-[#5C746C] sm:text-base">
                                Dokumentasi kegiatan PPM berdasarkan kategori yang dipilih.
                            </p>
                        </div>
                    </div>

                    <a href="{{ route('home') }}#kegiatan"
                        class="inline-flex items-center justify-center rounded-full border border-[#C9D8D0] bg-white px-5 py-2.5 text-sm font-semibold text-[#334B49] transition hover:bg-[#EDF4EF]">
                        Kembali ke kategori
                    </a>
                </div>
            </div>

            <div class="mb-6 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.18em] text-[#5E7A71]">Daftar Berita</p>
                    <h2 class="mt-2 text-2xl font-semibold text-[#183530]">Kategori {{ $categoryName }}</h2>
                </div>
                <p class="text-sm text-[#647A72]">
                    Menampilkan {{ $beritaItems->firstItem() ?? 0 }}-{{ $beritaItems->lastItem() ?? 0 }}
                    dari {{ $beritaItems->total() }} berita
                </p>
            </div>

            <div class="grid gap-4 sm:grid-cols-2 md:gap-6 lg:grid-cols-3">
                @forelse ($beritaItems as $news)
                    @php
                        $authorName = optional($news->user)->name ?? 'Admin PPM';
                        $publishedAt = $news->date
                            ? \Illuminate\Support\Carbon::parse($news->date)
                            : ($news->created_at ?: null);
                        $excerpt = \Illuminate\Support\Str::limit(strip_tags($news->content ?? ''), 150);
                    @endphp

                    <article class="group flex flex-col overflow-hidden rounded-[1.75rem] border border-[#E4EBE8] bg-white shadow-lg shadow-[#334B49]/8 transition duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-[#334B49]/12">
                        <div class="relative h-52 overflow-hidden bg-[#E8EFED] md:h-60">
                            <img src="{{ $news->image_url ? asset('storage/' . $news->image_url) : asset('images/assets/hero.png') }}" loading="lazy" alt="{{ $news->title }}"
                                class="absolute inset-0 h-full w-full object-cover object-center transition duration-500 group-hover:scale-105" />
                            <div class="absolute inset-0 bg-gradient-to-t from-[#233533]/65 via-[#233533]/10 to-transparent"></div>
                            <div class="absolute left-4 top-4">
                                <span class="rounded-full bg-white/85 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.22em] text-[#334B49] backdrop-blur">
                                    {{ $news->category }}
                                </span>
                            </div>
                            @if ($publishedAt)
                                <div class="absolute bottom-4 left-4 rounded-2xl bg-white/14 px-3 py-2 text-xs font-medium text-white backdrop-blur">
                                    {{ $publishedAt->format('d M Y') }}
                                </div>
                            @endif
                        </div>

                        <div class="flex flex-1 flex-col p-5 sm:p-6">
                            <h3 class="text-lg font-semibold leading-snug text-[#334B49]">
                                {{ $news->title }}
                            </h3>

                            @if ($excerpt)
                                <p class="mt-3 text-sm leading-7 text-[#5C716F]">{{ $excerpt }}</p>
                            @endif

                            <div class="mt-auto pt-6">
                                <div class="flex items-center justify-between gap-4 border-t border-[#DDE4E2] pt-5">
                                    <span class="text-sm font-semibold text-[#334B49]">{{ $authorName }}</span>
                                    <span class="text-xs uppercase tracking-[0.18em] text-[#7A908D]">Penulis</span>
                                </div>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="rounded-[1.75rem] border border-dashed border-[#C9D8D0] bg-white px-6 py-10 text-center shadow-[0_18px_44px_-34px_rgba(28,50,45,0.28)] sm:col-span-2 lg:col-span-3">
                        <p class="text-sm text-[#60766E]">Belum ada berita dalam kategori ini.</p>
                    </div>
                @endforelse
            </div>

            @if ($beritaItems->hasPages())
                <div class="mt-8 border-t border-[#D4E2DA] pt-5 text-[#35514A]">
                    {{ $beritaItems->onEachSide(1)->links('partials.pagination.pengumuman') }}
                </div>
            @endif
        </div>
    </section>
@endsection
