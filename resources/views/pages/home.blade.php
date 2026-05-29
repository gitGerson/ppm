@extends('layouts.app')
@section('content')

    <!-- Hero -->
    <section id="home" class="mx-auto w-full max-w-screen-xl px-4 pt-2 md:px-8 scroll-mt-24">
        <div class="overflow-hidden rounded-[2.5rem] border border-[#DDE4E2] bg-[#F8FAF8] shadow-xl/10">
            <div class="relative px-3 py-3 md:px-4 md:py-4" data-carousel="static">
                <div class="absolute inset-x-0 top-0 h-40 bg-[radial-gradient(circle_at_top_left,_rgba(150,177,173,0.18),_transparent_42%),radial-gradient(circle_at_top_right,_rgba(51,75,73,0.14),_transparent_36%)]"></div>

                <div class="relative overflow-hidden rounded-[2rem]">
                    <div class="relative min-h-[420px] w-full md:min-h-[520px]">
                        @foreach ($heroSlides as $index => $slide)
                            <div class="hidden duration-700 ease-in-out" @if ($index === 0) data-carousel-item="active" @else data-carousel-item @endif>
                                <img src="{{ $slide['image'] }}" class="absolute inset-0 h-full w-full object-cover"
                                    alt="{{ $slide['alt'] }}">
                            </div>
                        @endforeach

                        <div class="absolute inset-0 flex items-end">
                            <div class="w-full p-6 md:p-10">
                                <div class="max-w-2xl rounded-[2rem] border border-white/20 bg-white/12 p-6 text-white shadow-2xl shadow-black/20 backdrop-blur-sm md:p-8">
                                    <span class="inline-flex items-center rounded-full bg-white/18 px-4 py-1 text-xs font-semibold uppercase tracking-[0.24em] text-white/90">
                                        Pondok Pesantren Mahasiswa
                                    </span>
                                    <h1 class="mt-4 text-4xl font-bold leading-tight md:text-5xl">
                                        PPM Al-Kautsar
                                        <span class="block text-[#DDE4E2]">Bina Insani Purwokerto</span>
                                    </h1>
                                    <p class="mt-4 max-w-xl text-sm leading-7 text-white/85 md:text-base">
                                        Ruang pembinaan yang memadukan ilmu, adab, dan kedisiplinan untuk menyiapkan santri
                                        yang religius, bertumbuh, dan siap berkontribusi.
                                    </p>
                                    <div class="mt-6 flex flex-wrap gap-3">
                                        <span class="rounded-full bg-white/14 px-4 py-2 text-sm font-medium text-white/90">Pembinaan intensif</span>
                                        <span class="rounded-full bg-white/14 px-4 py-2 text-sm font-medium text-white/90">Lingkungan terarah</span>
                                        <span class="rounded-full bg-white/14 px-4 py-2 text-sm font-medium text-white/90">Penguatan karakter</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="button"
                    class="absolute left-6 top-1/2 z-30 flex -translate-y-1/2 items-center justify-center focus:outline-none"
                    data-carousel-prev>
                    <span
                        class="inline-flex h-12 w-12 items-center justify-center rounded-full border border-white/30 bg-white/16 text-white shadow-lg backdrop-blur-sm transition hover:bg-white/26 focus:ring-4 focus:ring-white/25">
                        <svg class="h-4 w-4 rtl:rotate-180" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 1 1 5l4 4" />
                        </svg>
                        <span class="sr-only">Previous</span>
                    </span>
                </button>
                <button type="button"
                    class="absolute right-6 top-1/2 z-30 flex -translate-y-1/2 items-center justify-center focus:outline-none"
                    data-carousel-next>
                    <span
                        class="inline-flex h-12 w-12 items-center justify-center rounded-full border border-white/30 bg-white/16 text-white shadow-lg backdrop-blur-sm transition hover:bg-white/26 focus:ring-4 focus:ring-white/25">
                        <svg class="h-4 w-4 rtl:rotate-180" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 9 4-4-4-4" />
                        </svg>
                        <span class="sr-only">Next</span>
                    </span>
                </button>
            </div>
        </div>
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
    @php
        $structureSlides = [
            [
                'image' => asset('images/assets/struktur1.png'),
                'alt' => 'Bagan struktur organisasi PPM 1',
            ],
            [
                'image' => asset('images/assets/struktur2.png'),
                'alt' => 'Bagan struktur organisasi PPM 2',
            ],
        ];
    @endphp
    <section id="struktur" class="mx-auto w-full max-w-screen-xl px-4 py-6 md:px-8 sm:py-8 lg:py-12 scroll-mt-24">
        <div class="overflow-hidden rounded-[2.5rem] border border-[#DDE4E2] bg-[#F8FAF8] shadow-xl/10">
            <div class="relative px-3 py-3 md:px-4 md:py-4" data-carousel="static">
                <div class="absolute inset-x-0 top-0 h-40 bg-[radial-gradient(circle_at_top_left,_rgba(150,177,173,0.18),_transparent_42%),radial-gradient(circle_at_top_right,_rgba(51,75,73,0.14),_transparent_36%)]"></div>

                <div class="relative overflow-hidden rounded-[2rem]">
                    <div class="relative min-h-[420px] w-full bg-[#EEF3F1] md:min-h-[520px]">
                        @foreach ($structureSlides as $index => $slide)
                            <div class="hidden duration-700 ease-in-out" @if ($index === 0) data-carousel-item="active" @else data-carousel-item @endif>
                                <img src="{{ $slide['image'] }}" class="absolute inset-0 h-full w-full object-contain bg-white p-6 md:p-10"
                                    alt="{{ $slide['alt'] }}">
                            </div>
                        @endforeach
                    </div>
                </div>

                <button type="button"
                    class="absolute left-6 top-1/2 z-30 flex -translate-y-1/2 items-center justify-center focus:outline-none"
                    data-carousel-prev>
                    <span
                        class="inline-flex h-12 w-12 items-center justify-center rounded-full border border-[#DDE4E2] bg-white/92 text-[#334B49] shadow-lg backdrop-blur-sm transition hover:bg-white focus:ring-4 focus:ring-[#DDE4E2]">
                        <svg class="h-4 w-4 rtl:rotate-180" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 1 1 5l4 4" />
                        </svg>
                        <span class="sr-only">Previous</span>
                    </span>
                </button>
                <button type="button"
                    class="absolute right-6 top-1/2 z-30 flex -translate-y-1/2 items-center justify-center focus:outline-none"
                    data-carousel-next>
                    <span
                        class="inline-flex h-12 w-12 items-center justify-center rounded-full border border-[#DDE4E2] bg-white/92 text-[#334B49] shadow-lg backdrop-blur-sm transition hover:bg-white focus:ring-4 focus:ring-[#DDE4E2]">
                        <svg class="h-4 w-4 rtl:rotate-180" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 9 4-4-4-4" />
                        </svg>
                        <span class="sr-only">Next</span>
                    </span>
                </button>
            </div>
        </div>
    </section>
    <!-- Struktur Organisasi - end -->

    <!-- Data Santri -->
    <section id="data-santri" class="bg-white py-6 sm:py-8 lg:py-12 scroll-mt-24">
        @php
            $defaultSantriMonth = $santriChart->last();
            $defaultMonthLabel = $defaultSantriMonth['month_label'] ?? 'Belum ada data';
            $defaultMaleCount = $defaultSantriMonth['male_count'] ?? 0;
            $defaultFemaleCount = $defaultSantriMonth['female_count'] ?? 0;
            $defaultTotalCount = $defaultMaleCount + $defaultFemaleCount;
        @endphp
        <div class="mx-auto max-w-screen-2xl px-4 md:px-8">
            <div class="overflow-hidden rounded-[2rem] border border-[#DDE4E2] bg-[#F8FAF8] shadow-xl/10">
                <div class="relative px-6 py-8 md:px-8 md:py-10">
                    <div class="absolute inset-x-0 top-0 h-40 bg-[radial-gradient(circle_at_top_left,_rgba(150,177,173,0.16),_transparent_42%),radial-gradient(circle_at_top_right,_rgba(51,75,73,0.12),_transparent_36%)]"></div>

                    <div class="relative mb-8 flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                        <div class="max-w-2xl">
                            <span class="inline-flex items-center rounded-full bg-[#DDE4E2] px-4 py-1 text-xs font-semibold uppercase tracking-[0.24em] text-[#334B49]">
                                Statistik Santri
                            </span>
                            <h2 class="mt-4 text-3xl font-bold text-[#334B49] md:text-4xl">Data Santri</h2>
                            <p class="mt-3 text-sm leading-7 text-[#5C716F] md:text-base">
                                Ringkasan jumlah santri berdasarkan periode pencatatan, dengan distribusi laki-laki dan perempuan.
                            </p>
                        </div>

                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
                            <div class="rounded-3xl border border-white/80 bg-white/85 px-4 py-3 shadow-lg shadow-[#334B49]/6 backdrop-blur">
                                <div class="text-[11px] font-semibold uppercase tracking-[0.22em] text-[#7A908D]">Total</div>
                                <div class="mt-2 text-2xl font-bold text-[#334B49]" id="totalCount">{{ $defaultTotalCount }}</div>
                            </div>
                            <div class="rounded-3xl border border-[#D7E4F5] bg-[#EEF5FF] px-4 py-3 shadow-lg shadow-[#334B49]/6">
                                <div class="text-[11px] font-semibold uppercase tracking-[0.22em] text-[#58748D]">Laki-laki</div>
                                <div class="mt-2 text-2xl font-bold text-[#334B49]" id="maleCount">{{ $defaultMaleCount }}</div>
                            </div>
                            <div class="rounded-3xl border border-[#F1D8E4] bg-[#FFF0F6] px-4 py-3 shadow-lg shadow-[#334B49]/6">
                                <div class="text-[11px] font-semibold uppercase tracking-[0.22em] text-[#8D5D75]">Perempuan</div>
                                <div class="mt-2 text-2xl font-bold text-[#334B49]" id="femaleCount">{{ $defaultFemaleCount }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="relative rounded-[1.75rem] border border-white/80 bg-white p-5 shadow-lg shadow-[#334B49]/8 md:p-6">
                        <div class="mb-6 flex flex-col gap-4 border-b border-[#E6EEEB] pb-5 md:flex-row md:items-center md:justify-between">
                            <div class="relative">
                                <div class="mb-2 text-[11px] font-semibold uppercase tracking-[0.22em] text-[#7A908D]">Periode Data</div>
                                <button id="monthButton" type="button"
                                    @if ($santriChart->isEmpty()) disabled @endif
                                    class="inline-flex items-center gap-3 rounded-2xl border border-[#DDE4E2] bg-[#F8FAF8] px-4 py-3 text-sm font-semibold text-[#334B49] shadow-sm transition hover:border-[#96B1AD] hover:bg-white disabled:cursor-not-allowed disabled:text-[#9EB0AD]">
                                    <span data-label>{{ $defaultMonthLabel }}</span>
                                    <svg class="h-3 w-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 10 6">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="m1 1 4 4 4-4" />
                                    </svg>
                                </button>

                                @if ($santriChart->isNotEmpty())
                                    <div id="monthDropdown"
                                        class="absolute left-0 z-10 mt-3 hidden w-56 overflow-hidden rounded-2xl border border-[#E6EEEB] bg-white shadow-xl shadow-[#334B49]/10">
                                        <ul class="py-2 text-sm text-[#334B49]">
                                            @foreach ($santriChart as $month)
                                                <li>
                                                    <a href="#" class="month-item block px-4 py-3 transition hover:bg-[#F3F6F5]"
                                                        data-month="{{ $month['month_key'] }}">
                                                        {{ $month['month_label'] }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>

                            <div class="flex flex-wrap items-center gap-2">
                                <span class="inline-flex items-center rounded-full bg-[#EEF5FF] px-3 py-2 text-xs font-semibold text-[#3B6488]">
                                    <span class="mr-2 h-2.5 w-2.5 rounded-full bg-[#5B8DB8]"></span>
                                    Laki-laki
                                </span>
                                <span class="inline-flex items-center rounded-full bg-[#FFF0F6] px-3 py-2 text-xs font-semibold text-[#8A5C72]">
                                    <span class="mr-2 h-2.5 w-2.5 rounded-full bg-[#D59AB7]"></span>
                                    Perempuan
                                </span>
                            </div>
                        </div>

                        @if ($santriChart->isEmpty())
                            <div class="rounded-[1.5rem] border border-dashed border-[#DDE4E2] bg-[#F8FAF8] py-16 text-center text-sm text-[#6E8481]">
                                Belum ada data santri yang dapat ditampilkan.
                            </div>
                        @else
                            <div class="rounded-[1.5rem] bg-[linear-gradient(180deg,_rgba(248,250,248,0.95),_rgba(255,255,255,1))] p-3 md:p-4">
                                <div id="column-chart"></div>
                            </div>
                        @endif
                    </div>
                </div>
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
            const totalCountEl = document.getElementById('totalCount');
            const maleCountEl = document.getElementById('maleCount');
            const femaleCountEl = document.getElementById('femaleCount');
            const labelEl = monthButton ? monthButton.querySelector('[data-label]') : null;

            if (!SANTRI_DATA.length) {
                if (labelEl) {
                    labelEl.textContent = 'Belum ada data';
                }
                if (totalCountEl) {
                    totalCountEl.textContent = totalCountEl.textContent || '0';
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
                        { name: 'Laki-laki', color: '#5B8DB8', data: [0] },
                        { name: 'Perempuan', color: '#D59AB7', data: [0] },
                    ];
                }

                return [
                    { name: 'Laki-laki', color: '#5B8DB8', data: [entry.male_count] },
                    { name: 'Perempuan', color: '#D59AB7', data: [entry.female_count] },
                ];
            };

            const updateCounts = (key) => {
                const entry = DATA_BY_MONTH[key];
                const maleCount = entry ? entry.male_count : 0;
                const femaleCount = entry ? entry.female_count : 0;

                if (totalCountEl) {
                    totalCountEl.textContent = maleCount + femaleCount;
                }
                if (maleCountEl) {
                    maleCountEl.textContent = maleCount;
                }
                if (femaleCountEl) {
                    femaleCountEl.textContent = femaleCount;
                }
                if (labelEl) {
                    labelEl.textContent = entry ? entry.month_label : 'Belum ada data';
                }
            };

            const options = {
                colors: ['#5B8DB8', '#D59AB7'],
                series: makeSeries(fallbackKey),
                chart: {
                    type: 'bar',
                    height: 380,
                    fontFamily: 'Inter, sans-serif',
                    toolbar: { show: false },
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '42%',
                        borderRadiusApplication: 'end',
                        borderRadius: 14,
                    },
                },
                xaxis: {
                    categories,
                    labels: {
                        show: true,
                        style: {
                            fontFamily: 'Inter, sans-serif',
                            cssClass: 'text-xs font-semibold fill-[#6E8481]',
                        },
                    },
                    axisBorder: { show: false },
                    axisTicks: { show: false },
                },
                legend: { show: false },
                tooltip: {
                    shared: false,
                    intersect: true,
                    style: { fontFamily: 'Inter, sans-serif' },
                    y: {
                        formatter: (value) => `${value} santri`,
                    },
                },
                grid: {
                    borderColor: '#E6EEEB',
                    strokeDashArray: 5,
                    xaxis: { lines: { show: false } },
                    yaxis: { lines: { show: true } },
                    padding: { left: 6, right: 12, top: 0, bottom: 0 },
                },
                dataLabels: { enabled: false },
                stroke: { show: true, width: 0, colors: ['transparent'] },
                yaxis: {
                    show: true,
                    min: 0,
                    forceNiceScale: true,
                    labels: {
                        style: {
                            colors: ['#7A908D'],
                            fontSize: '12px',
                            fontFamily: 'Inter, sans-serif',
                        },
                    },
                },
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
    <section id="kurikulum" class="bg-white py-10 sm:py-12 lg:py-16 scroll-mt-24">
        <div class="mx-auto max-w-screen-2xl px-4 md:px-8">
            <div class="overflow-hidden rounded-[2rem] border border-[#DDE4E2] bg-[#F8FAF8] shadow-xl/10">
                <div class="relative px-6 py-10 md:px-10 md:py-12">
                    <div class="absolute inset-x-0 top-0 h-40 bg-[radial-gradient(circle_at_top_right,_rgba(51,75,73,0.14),_transparent_48%),radial-gradient(circle_at_top_left,_rgba(150,177,173,0.18),_transparent_38%)]"></div>

                    <div class="relative mb-10 flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                        <div class="max-w-2xl">
                            <span class="inline-flex items-center rounded-full bg-[#DDE4E2] px-4 py-1 text-xs font-semibold uppercase tracking-[0.24em] text-[#334B49]">
                                Program Pembelajaran
                            </span>
                            <h2 class="mt-4 text-3xl font-bold text-[#334B49] md:text-4xl">Kurikulum Kami</h2>
                            <p class="mt-3 text-sm leading-7 text-[#5C716F] md:text-base">
                                Kurikulum PPM dirancang bertahap, terarah, dan adaptif untuk membantu santri berkembang
                                dari kemampuan dasar hingga pendalaman materi inti.
                            </p>
                        </div>

                        <div class="rounded-3xl border border-white/70 bg-white/80 px-5 py-4 shadow-lg shadow-[#334B49]/8 backdrop-blur">
                            <div class="text-sm font-semibold text-[#334B49]">Alur Pembinaan</div>
                            <div class="mt-2 text-sm text-[#5C716F]">Dasar bacaan, penguatan ritme belajar, evaluasi, hingga pendalaman hadist.</div>
                        </div>
                    </div>

                    <div class="relative grid gap-5 md:grid-cols-2 xl:grid-cols-5">
                        @foreach ($curriculumItems as $item)
                            @php
                                $accent = match ($item->theme) {
                                    'sand' => 'from-[#E8DCC8] to-[#F7F2EA]',
                                    'mint' => 'from-[#CFE1D7] to-[#EEF5F1]',
                                    'olive' => 'from-[#E3E8D3] to-[#F7F9F0]',
                                    'sky' => 'from-[#D9E4ED] to-[#F3F7FA]',
                                    default => 'from-[#DDE4E2] to-[#F5F7F6]',
                                };
                            @endphp
                            <article class="group flex h-full flex-col rounded-[1.75rem] border border-white/70 bg-white p-5 shadow-lg shadow-[#334B49]/8 transition duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-[#334B49]/12">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br {{ $accent }} text-[#334B49] shadow-inner shadow-white/80">
                                        @switch($item->icon)
                                            @case('book')
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20M6.5 17A2.5 2.5 0 0 0 4 19.5m2.5-2.5H20V5H6.5A2.5 2.5 0 0 0 4 7.5v12" />
                                                </svg>
                                                @break
                                            @case('layers')
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4 4 8l8 4 8-4-8-4Zm0 8-8 4 8 4 8-4-8-4Z" />
                                                </svg>
                                                @break
                                            @case('spark')
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13 3 4 14h6l-1 7 9-11h-6l1-7Z" />
                                                </svg>
                                                @break
                                            @case('shield')
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 3c2.6 2 5.8 3 9 3v6c0 5-3.4 8.8-9 10-5.6-1.2-9-5-9-10V6c3.2 0 6.4-1 9-3Z" />
                                                </svg>
                                                @break
                                            @default
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m12 3 2.2 4.7 5.1.7-3.7 3.7.9 5.1-4.5-2.5-4.5 2.5.9-5.1-3.7-3.7 5.1-.7L12 3Z" />
                                                </svg>
                                        @endswitch
                                    </div>

                                    <span class="rounded-full bg-[#F3F6F5] px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.22em] text-[#6E8481]">
                                        Materi
                                    </span>
                                </div>

                                <div class="mt-6 flex-1">
                                    <h3 class="text-xl font-semibold text-[#334B49]">{{ $item->title }}</h3>
                                    <p class="mt-3 text-sm leading-7 text-[#5C716F]">{{ $item->description }}</p>
                                </div>

                                <div class="mt-6 h-px w-full bg-gradient-to-r {{ $accent }}"></div>
                            </article>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Kurikulum - end -->

    <!-- Kegiatan Belajar Mengajar -->
    <section id="kegiatan" class="bg-white py-6 sm:py-8 lg:py-12 scroll-mt-24">
        <div class="mx-auto max-w-screen-2xl px-4 md:px-8">
            <div class="mb-10 flex flex-col gap-4 md:mb-14 lg:flex-row lg:items-end lg:justify-between">
                <div class="max-w-2xl">
                    <span class="inline-flex items-center rounded-full bg-[#DDE4E2] px-4 py-1 text-xs font-semibold uppercase tracking-[0.24em] text-[#334B49]">
                        Kabar PPM
                    </span>
                    <h2 class="mt-4 text-2xl font-bold text-[#334B49] md:text-3xl lg:text-4xl">Kegiatan Belajar Mengajar</h2>
                    <p class="mt-3 text-sm leading-7 text-[#5C716F] md:text-base">
                        Dokumentasi kegiatan, pembinaan, dan dinamika belajar santri yang berlangsung di lingkungan PPM.
                    </p>
                </div>

                <div class="rounded-3xl border border-[#DDE4E2] bg-[#F8FAF8] px-5 py-4 text-sm text-[#5C716F] shadow-lg shadow-[#334B49]/6">
                    Update terbaru seputar aktivitas santri dan program pembelajaran.
                </div>
            </div>

            <div class="grid gap-4 sm:grid-cols-2 md:gap-6 lg:grid-cols-3 xl:gap-8">
                @forelse ($beritaCategories as $category)
                    @php
                        $latest = $category['latest'];
                        $categoryImage = $latest?->image_url
                            ? asset('storage/' . $latest->image_url)
                            : asset('images/assets/hero.png');
                    @endphp
                    <a href="{{ route('berita.category', $category['slug']) }}"
                        class="group flex min-h-[26rem] flex-col overflow-hidden rounded-[1.75rem] border border-[#E4EBE8] bg-[#F8FAF8] shadow-lg shadow-[#334B49]/8 transition duration-300 hover:-translate-y-1 hover:bg-white hover:shadow-xl hover:shadow-[#334B49]/12">
                        <div class="relative h-48 overflow-hidden bg-[#E8EFED]">
                            <img src="{{ $categoryImage }}" loading="lazy" alt="Kategori {{ $category['label'] }}"
                                class="absolute inset-0 h-full w-full object-cover object-center transition duration-500 group-hover:scale-105" />
                            <div class="absolute inset-0 bg-gradient-to-t from-[#233533]/70 via-[#233533]/10 to-transparent"></div>
                            <div class="absolute left-4 top-4 flex items-center gap-2">
                                <span class="inline-flex rounded-full bg-white/90 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.22em] text-[#334B49] backdrop-blur">
                                    Kategori
                                </span>
                            </div>
                        </div>

                        <div class="flex flex-1 flex-col justify-between p-5 sm:p-6">
                            <div>
                                <div class="flex items-start justify-between gap-4">
                                    <span class="rounded-full bg-[#DDE4E2] px-3 py-1 text-xs font-semibold text-[#334B49]">
                                        {{ $category['count'] }} berita
                                    </span>
                                </div>

                                <h3 class="mt-5 text-2xl font-semibold leading-tight text-[#334B49]">
                                    {{ $category['label'] }}
                                </h3>

                                <p class="mt-4 text-sm leading-7 text-[#5C716F]">
                                    Lihat daftar berita dan dokumentasi kegiatan berdasarkan kategori {{ $category['label'] }}.
                                </p>
                            </div>

                            <div class="mt-8 flex items-center justify-between gap-4 border-t border-[#DDE4E2] pt-5">
                                <span class="text-xs uppercase tracking-[0.18em] text-[#7A908D]">
                                    Lihat daftar
                                </span>

                                <span class="inline-flex h-11 w-11 items-center justify-center rounded-full bg-[#334B49] text-white transition group-hover:bg-[#496764]">
                                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M3 10a1 1 0 011-1h9.586l-3.293-3.293a1 1 0 111.414-1.414l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414-1.414L13.586 11H4a1 1 0 01-1-1z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="sr-only">Lihat kategori {{ $category['label'] }}</span>
                                </span>
                            </div>
                        </div>
                    </a>
                @empty
                    <p class="text-sm text-gray-600">Belum ada berita kegiatan terbaru.</p>
                @endforelse
            </div>
        </div>
    </section>
    <!-- Kegiatan Belajar Mengajar - end -->

    <!-- Event -->
    <section id="event" class="bg-white py-6 sm:py-8 lg:py-12 scroll-mt-24">
        <div class="mx-auto max-w-screen-2xl px-4 md:px-8">
            <div class="mb-8 flex flex-col gap-4 md:mb-10 lg:flex-row lg:items-end lg:justify-between">
                <div class="max-w-2xl">
                    <span class="inline-flex items-center rounded-full bg-[#DDE4E2] px-4 py-1 text-xs font-semibold uppercase tracking-[0.24em] text-[#334B49]">
                        Video Event
                    </span>
                    <h2 class="mt-4 text-2xl font-bold text-[#334B49] md:text-3xl lg:text-4xl">Event PPM</h2>
                    <p class="mt-3 text-sm leading-7 text-[#5C716F] md:text-base">
                        Dokumentasi video kegiatan dan momen penting PPM Al-Kautsar Bina Insani.
                    </p>
                </div>

                <div class="rounded-3xl border border-[#DDE4E2] bg-[#F8FAF8] px-5 py-4 text-sm text-[#5C716F] shadow-lg shadow-[#334B49]/6">
                    Geser video untuk melihat dokumentasi event lainnya.
                </div>
            </div>

            <div class="overflow-hidden rounded-[2.5rem] border border-[#DDE4E2] bg-[#F8FAF8] shadow-xl/10">
                @if ($eventItems->isNotEmpty())
                    <div class="relative px-3 py-3 md:px-4 md:py-4" data-carousel="static" data-event-carousel>
                        <div class="absolute inset-x-0 top-0 h-40 bg-[radial-gradient(circle_at_top_left,_rgba(150,177,173,0.18),_transparent_42%),radial-gradient(circle_at_top_right,_rgba(51,75,73,0.14),_transparent_36%)]"></div>

                        <div class="relative overflow-hidden rounded-[2rem] bg-[#233533]">
                            <div class="relative min-h-[420px] w-full md:min-h-[560px]">
                                @foreach ($eventItems as $index => $event)
                                    @php
                                        $videoExtension = \Illuminate\Support\Str::lower(pathinfo($event->asset_video, PATHINFO_EXTENSION));
                                        $videoType = match ($videoExtension) {
                                            'webm' => 'video/webm',
                                            'ogg', 'ogv' => 'video/ogg',
                                            default => 'video/mp4',
                                        };
                                    @endphp
                                    <div class="hidden duration-700 ease-in-out" @if ($index === 0) data-carousel-item="active" @else data-carousel-item @endif>
                                        <video class="absolute inset-0 h-full w-full bg-black object-contain" controls muted playsinline preload="metadata" data-event-video>
                                            <source src="{{ asset('storage/'.$event->asset_video) }}" type="{{ $videoType }}">
                                            Browser Anda tidak mendukung pemutar video.
                                        </video>

                                        <div class="pointer-events-none absolute inset-x-0 bottom-0 bg-gradient-to-t from-[#111B1A]/90 via-[#111B1A]/35 to-transparent p-5 md:p-8">
                                            <h3 class="max-w-3xl text-xl font-semibold text-white md:text-2xl">{{ $event->judul }}</h3>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        @if ($eventItems->count() > 1)
                            <button type="button"
                                class="absolute left-6 top-1/2 z-30 flex -translate-y-1/2 items-center justify-center focus:outline-none"
                                data-carousel-prev>
                                <span class="inline-flex h-12 w-12 items-center justify-center rounded-full border border-white/30 bg-white/16 text-white shadow-lg backdrop-blur-sm transition hover:bg-white/26 focus:ring-4 focus:ring-white/25">
                                    <svg class="h-4 w-4 rtl:rotate-180" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 1 1 5l4 4" />
                                    </svg>
                                    <span class="sr-only">Previous</span>
                                </span>
                            </button>
                            <button type="button"
                                class="absolute right-6 top-1/2 z-30 flex -translate-y-1/2 items-center justify-center focus:outline-none"
                                data-carousel-next>
                                <span class="inline-flex h-12 w-12 items-center justify-center rounded-full border border-white/30 bg-white/16 text-white shadow-lg backdrop-blur-sm transition hover:bg-white/26 focus:ring-4 focus:ring-white/25">
                                    <svg class="h-4 w-4 rtl:rotate-180" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="m1 9 4-4-4-4" />
                                    </svg>
                                    <span class="sr-only">Next</span>
                                </span>
                            </button>
                        @endif
                    </div>
                @else
                    <div class="px-6 py-16 text-center text-sm text-[#6E8481]">
                        Belum ada video event yang dapat ditampilkan.
                    </div>
                @endif
            </div>
        </div>
    </section>
    <!-- Event - end -->

    <!-- Lingkungan -->
    <section id="lingkungan" class="bg-[#F8FAF8] py-6 sm:py-8 lg:py-12 scroll-mt-24">
        <div class="mx-auto max-w-screen-2xl px-4 md:px-8">
            <div class="mb-8 flex flex-col gap-4 md:mb-10 lg:flex-row lg:items-end lg:justify-between">
                <div class="max-w-2xl">
                    <span class="inline-flex items-center rounded-full bg-[#DDE4E2] px-4 py-1 text-xs font-semibold uppercase tracking-[0.24em] text-[#334B49]">
                        Virtual Tour
                    </span>
                    <h2 class="mt-4 text-2xl font-bold text-[#334B49] md:text-3xl lg:text-4xl">Lingkungan PPM</h2>
                    <p class="mt-3 text-sm leading-7 text-[#5C716F] md:text-base">
                        Jelajahi suasana lingkungan PPM Al-Kautsar Bina Insani secara interaktif.
                    </p>
                </div>

                <div class="rounded-3xl border border-[#DDE4E2] bg-white px-5 py-4 text-sm text-[#5C716F] shadow-lg shadow-[#334B49]/6">
                    Tampilan 360 derajat area pondok dan fasilitas pendukung.
                </div>
            </div>

            <div class="overflow-hidden rounded-[2rem] border border-[#DDE4E2] bg-white shadow-xl shadow-[#334B49]/10">
                <div class="min-h-[650px] w-full">
                    <script async src="https://static.theasys.io/embed.js" data-theasys="TLHlfVRvvCHF0JbFPWRF91JlKZDQ3H" data-height="650"></script>
                </div>
            </div>
        </div>
    </section>
    <!-- Lingkungan - end -->

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
        <div class="mx-auto max-w-screen-xl px-4 md:px-8">
            <div class="overflow-hidden rounded-[2.5rem] border border-[#DDE4E2] bg-[#F8FAF8] shadow-xl/10">
                <div class="relative px-6 py-8 md:px-8 md:py-10">
                    <div class="absolute inset-x-0 top-0 h-40 bg-[radial-gradient(circle_at_top_left,_rgba(150,177,173,0.18),_transparent_42%),radial-gradient(circle_at_top_right,_rgba(51,75,73,0.14),_transparent_36%)]"></div>

                    <div class="relative grid gap-6 lg:grid-cols-[1fr_0.9fr] lg:items-center">
                        <div class="rounded-[2rem] bg-[#334B49] px-6 py-8 text-white shadow-2xl shadow-[#334B49]/20 md:px-8 md:py-10">
                            <span class="inline-flex items-center rounded-full bg-white/14 px-4 py-1 text-xs font-semibold uppercase tracking-[0.24em] text-white/85">
                                Newsletter PPM
                            </span>
                            <h2 class="mt-4 text-3xl font-bold leading-tight md:text-4xl">Dapatkan Informasi Terbaru</h2>
                            <p class="mt-4 max-w-2xl text-sm leading-7 text-white/80 md:text-base">
                                Daftarkan alamat email Anda untuk menerima kabar kegiatan, informasi pembinaan, dan pembaruan pendaftaran terbaru dari PPM.
                            </p>

                            <form class="mt-8 flex flex-col gap-3 lg:flex-row lg:items-stretch" aria-label="Formulir sign up">
                                <label class="sr-only" for="signup-email">Alamat Email</label>
                                <input id="signup-email" type="email" name="signup_email"
                                    class="w-full rounded-2xl border border-white/20 bg-white/10 px-5 py-4 text-sm text-white placeholder:text-white/55 focus:border-white focus:outline-none focus:ring-2 focus:ring-white/40"
                                    placeholder="Masukkan email aktif Anda" required>
                                <button type="submit"
                                    class="inline-flex min-w-36 items-center justify-center rounded-2xl bg-white px-6 py-4 text-sm font-semibold text-[#334B49] shadow-lg transition hover:bg-[#F3F6F5]">
                                    Sign Up
                                </button>
                            </form>

                            <p class="mt-4 text-xs leading-6 text-white/60">
                                Kami menghargai privasi Anda. Anda dapat berhenti berlangganan kapan saja.
                            </p>
                        </div>

                        <div class="rounded-[2rem] border border-white/80 bg-white/92 p-6 shadow-lg shadow-[#334B49]/8 md:p-8">
                            <div class="text-sm font-semibold uppercase tracking-[0.22em] text-[#7A908D]">Manfaat Berlangganan</div>
                            <div class="mt-6 space-y-4">
                                <div class="rounded-2xl bg-[#F3F6F5] p-4">
                                    <div class="text-base font-semibold text-[#334B49]">Info kegiatan terbaru</div>
                                    <p class="mt-2 text-sm leading-7 text-[#5C716F]">Terima pembaruan kegiatan belajar, agenda santri, dan momen penting PPM secara berkala.</p>
                                </div>
                                <div class="rounded-2xl bg-[#F8FAF8] p-4">
                                    <div class="text-base font-semibold text-[#334B49]">Pembaruan pendaftaran</div>
                                    <p class="mt-2 text-sm leading-7 text-[#5C716F]">Dapatkan kabar lebih cepat saat ada informasi penting seputar pendaftaran santri.</p>
                                </div>
                                <div class="rounded-2xl bg-[#F3F6F5] p-4">
                                    <div class="text-base font-semibold text-[#334B49]">Komunikasi lebih mudah</div>
                                    <p class="mt-2 text-sm leading-7 text-[#5C716F]">Tetap terhubung dengan informasi resmi tanpa perlu memantau situs setiap saat.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Sign Up - end -->

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('[data-event-carousel]').forEach((carousel) => {
                const slides = Array.from(carousel.querySelectorAll('[data-carousel-item]'));

                const syncEventVideos = () => {
                    slides.forEach((slide) => {
                        const video = slide.querySelector('[data-event-video]');

                        if (!video) {
                            return;
                        }

                        if (slide.classList.contains('hidden')) {
                            video.pause();
                            video.currentTime = 0;

                            return;
                        }

                        video.play().catch(() => {});
                    });
                };

                slides.forEach((slide) => {
                    new MutationObserver(syncEventVideos).observe(slide, {
                        attributes: true,
                        attributeFilter: ['class'],
                    });
                });

                syncEventVideos();
            });

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
