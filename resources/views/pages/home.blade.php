@extends('layouts.app')
@section('content')

    <!-- Hero -->
    <section id="home" class="relative w-full max-w-screen-xl mx-auto scroll-mt-24" data-carousel="static">
        <!-- Carousel wrapper -->
        <div class="relative w-full overflow-hidden rounded-lg">
            <div class="relative w-full" style="padding-top:50%;">
                <!-- Item 1 -->
                <div class="hidden duration-700 ease-in-out" data-carousel-item="active">
                    <img src="{{ asset('images/assets/hero.png') }}" class="absolute inset-0 h-full w-full object-cover"
                        alt="Hero">
                </div>
                <!-- Item 2 -->
                <div class="hidden duration-700 ease-in-out" data-carousel-item>
                    <img src="{{ asset('images/assets/hero2.png') }}" class="absolute inset-0 h-full w-full object-cover"
                        alt="Hero">
                </div>
            </div>
        </div>
        <!-- Slider controls -->
        <button type="button"
            class="absolute top-0 start-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
            data-carousel-prev>
            <span
                class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
                <svg class="w-4 h-4 text-white dark:text-gray-800 rtl:rotate-180" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5 1 1 5l4 4" />
                </svg>
                <span class="sr-only">Previous</span>
            </span>
        </button>
        <button type="button"
            class="absolute top-0 end-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
            data-carousel-next>
            <span
                class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
                <svg class="w-4 h-4 text-white dark:text-gray-800 rtl:rotate-180" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m1 9 4-4-4-4" />
                </svg>
                <span class="sr-only">Next</span>
            </span>
        </button>
    </section>
    <!-- Hero - end -->

    <!-- About Us -->
    <section id="about" class="bg-white py-6 sm:py-8 lg:py-12 scroll-mt-24">
        <div class="mx-auto max-w-screen-xl px-4 md:px-8">
            <div class="grid gap-8 md:grid-cols-2 lg:gap-12">
                <div>
                    <div class="h-64 overflow-hidden rounded-lg bg-gray-100 shadow-lg md:h-auto">
                        <img src="{{asset('images/assets/aboutus.png')}}" loading="lazy" alt="Photo by Martin Sanchez"
                            class="h-full w-full object-cover object-center" />
                    </div>
                </div>

                <div class="md:pt-8">

                    <p class="mb-6 text-gray-900 sm:text-lg md:mb-8 text-justify">PPM (Pondok Pesantren Mahasiswa )
                        Al-Kautsar Bina Insani merupakan salah satu pondok pesantren yang berdiri di Wilayah Purwokerto,
                        Jawa Tengah. Terletak di Kelurahan Sumampir, Purwokerto Utara, PPM Al-Kautsar Bina Insani telah
                        berdiri 11 tahun lamanya sejak tahun 2014 </p>

                    <h2 class="mb-2 text-center text-xl font-semibold text-gray-800 sm:text-2xl md:mb-4 md:text-left">VISI
                        PPM</h2>
                    <p class="mb-6 text-gray-900 text-justify sm:text-lg md:mb-8 bg-[#96B1AD80] p-4 rounded-lg">Membentuk
                        generasi penerus yang profesional religius, serta menjadi mubaligh yang sarjana dan sarjana yang
                        mubaligh</p>

                    <h2 class="mb-2 text-center text-xl font-semibold text-gray-800 sm:text-2xl md:mb-4 md:text-left">MISI
                        PPM</h2>
                    <ul
                        class="mb-6 bg-[#96B1AD80] p-4 rounded-lg text-gray-900 sm:text-lg md:mb-8 list-disc text-justify list-outside pl-8 text-left">
                        <li>Melaksanakan program pembinaan secara intensif dan berkesinambungan.</li>
                        <li>Meningkatkan softskill santri dan melancarkan kuliah santri.</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <!-- About Us - end -->

    <!-- Struktur Organisasi -->
    <section id="struktur" class="relative w-full max-w-screen-xl mx-auto shadow-xl/30 scroll-mt-24" data-carousel="static">
        <!-- Carousel wrapper -->
        <div class="relative w-full overflow-hidden rounded-lg">
            <div class="relative w-full" style="padding-top:50%;">
                <!-- Item 1 -->
                <div class="hidden duration-700 ease-in-out" data-carousel-item>
                    <img src="{{ asset('images/assets/struktur1.png') }}"
                        class="absolute inset-0 h-full w-full object-cover" alt="Hero">
                </div>
                <!-- Item 2 -->
                <div class="hidden duration-700 ease-in-out" data-carousel-item="active">
                    <img src="{{ asset('images/assets/struktur2.png') }}"
                        class="absolute inset-0 h-full w-full object-cover" alt="Hero">
                </div>
            </div>
        </div>
        <!-- Slider controls -->
        <button type="button"
            class="absolute top-0 start-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
            data-carousel-prev>
            <span
                class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
                <svg class="w-4 h-4 text-white dark:text-gray-800 rtl:rotate-180" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5 1 1 5l4 4" />
                </svg>
                <span class="sr-only">Previous</span>
            </span>
        </button>
        <button type="button"
            class="absolute top-0 end-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
            data-carousel-next>
            <span
                class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
                <svg class="w-4 h-4 text-white dark:text-gray-800 rtl:rotate-180" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m1 9 4-4-4-4" />
                </svg>
                <span class="sr-only">Next</span>
            </span>
        </button>
    </section>
    <!-- Struktur Organisasi - end -->

    <!-- Data Santri -->
    <section id="data-santri" class="bg-white py-6 sm:py-8 lg:py-12 scroll-mt-24">
        @php
            $defaultSantriMonth = $santriChart->last();
            $defaultMonthLabel = $defaultSantriMonth['month_label'] ?? 'Belum ada data';
            $defaultMaleCount = $defaultSantriMonth['male_count'] ?? 0;
            $defaultFemaleCount = $defaultSantriMonth['female_count'] ?? 0;
        @endphp
        <div class="mx-auto max-w-screen-3xl px-4 md:px-8">
            <div class="mx-auto w-full max-w-3xl rounded-lg bg-white p-4 shadow-xl/30 md:p-6">
                <div class="mb-5 md:mb-8">
                    <h2 class="mb-4 text-center text-2xl font-bold text-gray-800 md:mb-6 lg:text-3xl">Data Santri
                    </h2>
                </div>
                <div class="relative mb-4 flex items-center justify-between border-b border-gray-200 pb-4">
                    <div class="relative">
                        <button id="monthButton" type="button"
                            @if ($santriChart->isEmpty()) disabled @endif
                            class="inline-flex items-center text-sm font-medium text-gray-600 hover:text-gray-900 disabled:cursor-not-allowed disabled:text-gray-400">
                            <span data-label>{{ $defaultMonthLabel }}</span>
                            <svg class="ms-1.5 w-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 10 6">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m1 1 4 4 4-4" />
                            </svg>
                        </button>

                        @if ($santriChart->isNotEmpty())
                            <div id="monthDropdown"
                                class="absolute left-0 z-10 mt-2 w-44 divide-y divide-gray-100 rounded-lg bg-white shadow-sm">
                                <ul class="py-2 text-sm text-gray-700">
                                    @foreach ($santriChart as $month)
                                        <li>
                                            <a href="#" class="month-item block px-4 py-2 hover:bg-gray-100"
                                                data-month="{{ $month['month_key'] }}">
                                                {{ $month['month_label'] }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                    <div class="flex items-center gap-2">
                        <span
                            class="inline-flex items-center rounded-md bg-blue-100 px-2.5 py-1 text-xs font-medium text-blue-800">
                            <svg class="me-1 h-3.5 w-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="currentColor" viewBox="0 0 20 19">
                                <path
                                    d="M14.5 0A3.987 3.987 0 0 0 11 2.1a4.977 4.977 0 0 1 3.9 5.858A3.989 3.989 0 0 0 14.5 0ZM9 13h2a4 4 0 0 1 4 4v2H5v-2a4 4 0 0 1 4-4Z" />
                                <path d="M5 19h10v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2Z" />
                            </svg>
                            Laki-laki:&nbsp;<span id="maleCount">{{ $defaultMaleCount }}</span>
                        </span>
                        <span
                            class="inline-flex items-center rounded-md bg-pink-100 px-2.5 py-1 text-xs font-medium text-pink-800">
                            <svg class="me-1 h-3.5 w-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="currentColor" viewBox="0 0 20 19">
                                <path
                                    d="M14.5 0A3.987 3.987 0 0 0 11 2.1a4.977 4.977 0 0 1 3.9 5.858A3.989 3.989 0 0 0 14.5 0ZM9 13h2a4 4 0 0 1 4 4v2H5v-2a4 4 0 0 1 4-4Z" />
                                <path d="M5 19h10v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2Z" />
                            </svg>
                            Perempuan:&nbsp;<span id="femaleCount">{{ $defaultFemaleCount }}</span>
                        </span>
                    </div>
                </div>

                @if ($santriChart->isEmpty())
                    <div
                        class="rounded-lg border border-dashed border-gray-200 bg-gray-50 py-12 text-center text-sm text-gray-500">
                        Belum ada data santri yang dapat ditampilkan.
                    </div>
                @else
                    <div id="column-chart"></div>
                @endif
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.46.0/dist/apexcharts.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const SANTRI_DATA = @json($santriChart);
            const DEFAULT_MONTH_KEY = @json($santriDefaultMonthKey);
            const DATA_BY_MONTH = SANTRI_DATA.reduce((acc, item) => {
                acc[item.month_key] = item;
                return acc;
            }, {});
            const monthButton = document.getElementById('monthButton');
            const monthDropdown = document.getElementById('monthDropdown');
            const maleCountEl = document.getElementById('maleCount');
            const femaleCountEl = document.getElementById('femaleCount');
            const labelEl = monthButton ? monthButton.querySelector('[data-label]') : null;

            if (!SANTRI_DATA.length) {
                if (labelEl) {
                    labelEl.textContent = 'Belum ada data';
                }
                if (maleCountEl) {
                    maleCountEl.textContent = maleCountEl.textContent || '0';
                }
                if (femaleCountEl) {
                    femaleCountEl.textContent = femaleCountEl.textContent || '0';
                }
                return;
            }

            const fallbackKey = DEFAULT_MONTH_KEY && DATA_BY_MONTH[DEFAULT_MONTH_KEY]
                ? DEFAULT_MONTH_KEY
                : SANTRI_DATA[SANTRI_DATA.length - 1].month_key;

            const categories = ['Santri'];

            const makeSeries = (key) => {
                const entry = DATA_BY_MONTH[key];
                if (!entry) {
                    return [
                        { name: 'Laki-laki', color: '#1A56DB', data: [0] },
                        { name: 'Perempuan', color: '#FDBA8C', data: [0] },
                    ];
                }

                return [
                    { name: 'Laki-laki', color: '#1A56DB', data: [entry.male_count] },
                    { name: 'Perempuan', color: '#FDBA8C', data: [entry.female_count] },
                ];
            };

            const updateCounts = (key) => {
                const entry = DATA_BY_MONTH[key];
                if (maleCountEl) {
                    maleCountEl.textContent = entry ? entry.male_count : 0;
                }
                if (femaleCountEl) {
                    femaleCountEl.textContent = entry ? entry.female_count : 0;
                }
                if (labelEl) {
                    labelEl.textContent = entry ? entry.month_label : 'Belum ada data';
                }
            };

            const options = {
                colors: ['#1A56DB', '#FDBA8C'],
                series: makeSeries(fallbackKey),
                chart: {
                    type: 'bar',
                    height: 320,
                    fontFamily: 'Inter, sans-serif',
                    toolbar: { show: false },
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '45%',
                        borderRadiusApplication: 'end',
                        borderRadius: 8,
                    },
                },
                xaxis: {
                    categories,
                    labels: {
                        show: true,
                        style: {
                            fontFamily: 'Inter, sans-serif',
                            cssClass: 'text-xs font-normal fill-gray-500',
                        },
                    },
                    axisBorder: { show: false },
                    axisTicks: { show: false },
                },
                legend: { show: true },
                tooltip: {
                    shared: false,
                    intersect: true,
                    style: { fontFamily: 'Inter, sans-serif' },
                },
                grid: {
                    show: false,
                    strokeDashArray: 4,
                    padding: { left: 2, right: 2, top: -14 },
                },
                dataLabels: { enabled: false },
                stroke: { show: true, width: 0, colors: ['transparent'] },
                yaxis: { show: true },
                fill: { opacity: 1 },
            };

            let chart;

            if (document.getElementById('column-chart') && typeof ApexCharts !== 'undefined') {
                chart = new ApexCharts(document.getElementById('column-chart'), options);
                chart.render();
            }

            updateCounts(fallbackKey);

            if (monthButton && monthDropdown) {
                monthButton.addEventListener('click', () => {
                    monthDropdown.classList.toggle('hidden');
                });

                document.addEventListener('click', (event) => {
                    if (!monthButton.contains(event.target) && !monthDropdown.contains(event.target)) {
                        monthDropdown.classList.add('hidden');
                    }
                });
            }

            document.querySelectorAll('#monthDropdown .month-item').forEach((item) => {
                item.addEventListener('click', (event) => {
                    event.preventDefault();
                    const key = item.getAttribute('data-month');
                    if (!key) {
                        return;
                    }

                    if (chart) {
                        chart.updateSeries(makeSeries(key));
                    }

                    updateCounts(key);

                    if (monthDropdown) {
                        monthDropdown.classList.add('hidden');
                    }
                });
            });
        });
    </script>
    <!-- Data Santri - end -->

    <!-- Kurikulum -->
    <section id="kurikulum" class="bg-white py-6 sm:py-8 lg:py-12 scroll-mt-24">
        <div class="mx-auto max-w-screen-2xl px-4 md:px-8">
            <!-- text - start -->
            <div class="mb-10 md:mb-16">
                <h2 class="mb-4 text-center text-2xl font-bold text-gray-800 md:mb-6 lg:text-3xl">Kurikulum Kami
                </h2>
            </div>
            <!-- text - end -->

            <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-5 xl:gap-10">
                <!-- feature - start -->
                <div class="flex flex-col items-center bg-white rounded-xl shadow-xl/30 p-4">
                    <div
                        class="mb-6 flex h-12 w-12 items-center justify-center rounded-lg bg-indigo-500 text-white shadow-lg md:h-14 md:w-14 md:rounded-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    </div>

                    <h3 class="mb-2 text-center text-lg font-semibold md:text-xl">Pegon Bacaan</h3>
                </div>
                <!-- feature - end -->

                <!-- feature - start -->
                <div class="flex flex-col items-center bg-white rounded-xl shadow-xl/30 p-4">
                    <div
                        class="mb-6 flex h-12 w-12 items-center justify-center rounded-lg bg-indigo-500 text-white shadow-lg md:h-14 md:w-14 md:rounded-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>

                    <h3 class="mb-2 text-center text-lg font-semibold md:text-xl">Lambatan</h3>
                </div>
                <!-- feature - end -->

                <!-- feature - start -->
                <div class="flex flex-col items-center bg-white rounded-xl shadow-xl/30 p-4">
                    <div
                        class="mb-6 flex h-12 w-12 items-center justify-center rounded-lg bg-indigo-500 text-white shadow-lg md:h-14 md:w-14 md:rounded-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z" />
                        </svg>
                    </div>

                    <h3 class="mb-2 text-center text-lg font-semibold md:text-xl">Cepatan</h3>
                </div>
                <!-- feature - end -->

                <!-- feature - start -->
                <div class="flex flex-col items-center bg-white rounded-xl shadow-xl/30 p-4">
                    <div
                        class="mb-6 flex h-12 w-12 items-center justify-center rounded-lg bg-indigo-500 text-white shadow-lg md:h-14 md:w-14 md:rounded-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>

                    <h3 class="mb-2 text-center text-lg font-semibold md:text-xl">Saringan</h3>
                </div>
                <!-- feature - end -->

                <!-- feature - start -->
                <div class="flex flex-col items-center bg-white rounded-xl shadow-xl/30 p-4">
                    <div
                        class="mb-6 flex h-12 w-12 items-center justify-center rounded-lg bg-indigo-500 text-white shadow-lg md:h-14 md:w-14 md:rounded-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>

                    <h3 class="mb-2 text-center text-lg font-semibold md:text-xl">Hadist Besar</h3>
                </div>
                <!-- feature - end -->
            </div>
        </div>
    </section>
    <!-- Kurikulum - end -->

    <!-- Kegiatan Belajar Mengajar -->
    <section id="kegiatan" class="bg-white py-6 sm:py-8 lg:py-12 scroll-mt-24">
        <div class="mx-auto max-w-screen-2xl px-4 md:px-8">
            <!-- text - start -->
            <div class="mb-10 md:mb-16">
                <h2 class="mb-4 text-center text-2xl font-bold text-gray-800 md:mb-6 lg:text-3xl">Kegiatan Belajar Mengajar</h2>
            </div>
            <!-- text - end -->

            <div class="grid gap-4 sm:grid-cols-2 md:gap-6 lg:grid-cols-3 xl:grid-cols-4 xl:gap-8">
                @forelse ($beritaItems as $news)
                    @php
                        $authorName = optional($news->user)->name ?? 'Admin PPM';
                        $publishedAt = $news->date
                            ? \Illuminate\Support\Carbon::parse($news->date)
                            : ($news->created_at ?: null);
                        $authorInitial = \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($authorName, 0, 1));
                        $excerpt = \Illuminate\Support\Str::limit(strip_tags($news->content ?? ''), 140);
                    @endphp
                    <article class="flex flex-col overflow-hidden rounded-lg border bg-white">
                        <a href="#" class="group relative block h-48 overflow-hidden bg-gray-100 md:h-64">
                            <img src="{{ $news->image_url ? asset('storage/' . $news->image_url) : asset('images/assets/hero.png') }}" loading="lazy" alt="{{ $news->title }}"
                                class="absolute inset-0 h-full w-full object-cover object-center transition duration-200 group-hover:scale-110" />
                        </a>

                        <div class="flex flex-1 flex-col p-4 sm:p-6">
                            <h3 class="mb-2 text-lg font-semibold text-gray-800">
                                <a href="#" class="transition duration-100 hover:text-[#334B49]">
                                    {{ $news->title }}
                                </a>
                            </h3>

                            @if ($excerpt)
                                <p class="mb-8 text-gray-500">{{ $excerpt }}</p>
                            @endif

                            <div class="mt-auto flex items-end justify-between">
                                <div class="flex items-center gap-2">
                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center overflow-hidden rounded-full bg-[#DDE4E2] text-sm font-semibold text-[#334B49]">
                                        {{ $authorInitial }}
                                    </div>

                                    <div>
                                        <span class="block text-[#334B49]">{{ $authorName }}</span>
                                        @if ($publishedAt)
                                            <span class="block text-sm text-gray-400">{{ $publishedAt->format('d F Y') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <span class="rounded border px-2 py-1 text-sm text-gray-500">Berita</span>
                            </div>
                        </div>
                    </article>
                @empty
                    <p class="text-sm text-gray-600">Belum ada berita kegiatan terbaru.</p>
                @endforelse
            </div>

            </div>
        </div>
    </section>
    <!-- Kegiatan Belajar Mengajar - end -->

    <!-- Daftar -->
    <!-- <section id="daftar" class="bg-[#F5F7F6] py-6 sm:py-8 lg:py-12 scroll-mt-24">
        <div class="mx-auto max-w-screen-xl px-4 md:px-8">
            <div class="rounded-2xl bg-white px-6 py-10 text-center shadow-xl/30 md:px-10">
                <h2 class="text-2xl font-bold text-gray-800 md:text-3xl">Pendaftaran Santri</h2>
                <p class="mt-3 text-gray-600 md:text-lg">Hubungi tim administrasi kami untuk memulai proses pendaftaran santri baru.</p>
                <div class="mt-6 flex flex-wrap justify-center gap-3">
                    <a href="tel:02816842450"
                        class="inline-flex items-center justify-center rounded-lg bg-[#334B49] px-6 py-3 text-base font-semibold text-white shadow-lg transition hover:bg-[#2a3d3b]">Hubungi
                        via Telepon</a>
                    <a href="mailto:ppmalkautsarbinainsani@gmail.com"
                        class="inline-flex items-center justify-center rounded-lg border border-[#334B49] px-6 py-3 text-base font-semibold text-[#334B49] transition hover:bg-[#334B49] hover:text-white">Kirim
                        Email</a>
                </div>
            </div>
        </div>
    </section> -->
    <!-- Daftar - end -->

    <!-- Sign Up -->
    <section id="signup" class="bg-white py-6 sm:py-8 lg:py-12 scroll-mt-24">
        <div class="mx-auto max-w-screen-md px-4 md:px-8">
            <div class="rounded-2xl bg-[#334B49] px-6 py-10 text-center text-white shadow-xl/30 md:px-12">
                <h2 class="text-2xl font-bold md:text-3xl">Dapatkan Informasi Terbaru</h2>
                <p class="mt-3 text-white/80 md:text-lg">Daftarkan alamat email Anda untuk menerima kabar kegiatan dan pendaftaran terbaru.</p>
                <form class="mt-6 flex flex-col gap-3 sm:flex-row sm:items-stretch" aria-label="Formulir sign up">
                    <label class="sr-only" for="signup-email">Alamat Email</label>
                    <input id="signup-email" type="email" name="signup_email"
                        class="w-full rounded-lg border border-white/40 bg-white/10 px-4 py-3 text-sm text-white placeholder:text-white/60 focus:border-white focus:outline-none focus:ring-2 focus:ring-white/60"
                        placeholder="Email Anda" required>
                    <button type="submit"
                        class="inline-flex items-center justify-center rounded-lg bg-white px-6 py-3 text-sm font-semibold text-[#334B49] transition hover:bg-gray-100">Sign
                        Up</button>
                </form>
                <p class="mt-3 text-xs text-white/60">Kami menghargai privasi Anda. Anda dapat berhenti berlangganan kapan saja.</p>
            </div>
        </div>
    </section>
    <!-- Sign Up - end -->

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const navLinks = Array.from(document.querySelectorAll('[data-scroll-target]'));
            if (!navLinks.length) {
                return;
            }

            const trackedSections = navLinks
                .map((link) => {
                    const id = link.dataset.scrollTarget;
                    const element = document.getElementById(id);
                    return element ? { id, element } : null;
                })
                .filter(Boolean);

            if (!trackedSections.length) {
                return;
            }

            let activeId = null;
            const activeClass = 'underline';

            const updateActive = (id) => {
                if (!id || id === activeId) {
                    return;
                }

                navLinks.forEach((link) => {
                    const isActive = link.dataset.scrollTarget === id;
                    link.classList.toggle(activeClass, isActive);
                    if (isActive) {
                        link.setAttribute('aria-current', 'page');
                    } else {
                        link.removeAttribute('aria-current');
                    }
                });

                activeId = id;
            };

            const observer = new IntersectionObserver((entries) => {
                const visible = entries
                    .filter((entry) => entry.isIntersecting)
                    .sort((a, b) => b.intersectionRatio - a.intersectionRatio);

                if (visible.length) {
                    updateActive(visible[0].target.id);
                }
            }, {
                rootMargin: '-35% 0px -55% 0px',
                threshold: [0.1, 0.25, 0.5, 0.75]
            });

            trackedSections.forEach(({ element }) => observer.observe(element));

            const setActiveFromHash = () => {
                const hash = window.location.hash.replace('#', '');
                if (hash && trackedSections.some(({ id }) => id === hash)) {
                    updateActive(hash);
                }
            };

            setActiveFromHash();
            if (!activeId) {
                updateActive(trackedSections[0].id);
            }

            window.addEventListener('hashchange', setActiveFromHash);
        });
    </script>

@endsection
