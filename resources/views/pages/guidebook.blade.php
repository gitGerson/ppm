@extends('layouts.app')

@section('content')
    <section class="bg-[linear-gradient(180deg,#f7fbf8_0%,#ffffff_24%,#f4f7f3_100%)] py-10 sm:py-12 lg:py-16">
        <div class="mx-auto max-w-7xl px-4 md:px-8">
            <div class="mb-8 rounded-[2rem] border border-[#D9E6DE] bg-[#F7FBF8] p-6 shadow-[0_22px_55px_-35px_rgba(28,50,45,0.35)] sm:p-8">
                <div class="max-w-3xl space-y-4">
                    <span class="inline-flex rounded-full border border-[#C9D8D0] bg-white px-4 py-1 text-xs font-semibold uppercase tracking-[0.24em] text-[#4B655D]">
                        Panduan PPM
                    </span>
                    <div class="space-y-3">
                        <h1 class="text-3xl font-semibold tracking-tight text-[#183530] sm:text-4xl">
                            Guidebook
                        </h1>
                        <p class="max-w-2xl text-sm leading-7 text-[#5C746C] sm:text-base">
                            Baca panduan resmi PPM Al-Kautsar Bina Insani secara digital.
                        </p>
                    </div>
                </div>
            </div>

            <div class="overflow-hidden rounded-[2rem] border border-[#D9E6DE] bg-white p-3 shadow-[0_24px_60px_-35px_rgba(28,50,45,0.35)] sm:p-4">
                <iframe
                    allowfullscreen="allowfullscreen"
                    allow="clipboard-write"
                    scrolling="no"
                    class="fp-iframe h-[520px] w-full rounded-[1.5rem] border border-[#D9E6DE] sm:h-[680px] lg:h-[780px]"
                    src="https://heyzine.com/flip-book/afd2bf2064.html"
                    title="Guidebook PPM Al-Kautsar Bina Insani"></iframe>
            </div>
        </div>
    </section>
@endsection
