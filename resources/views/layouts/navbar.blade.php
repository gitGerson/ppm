<nav class="relative bg-white border-gray-200">
    @php
        $isHome = request()->routeIs('home');
        $homeUrl = route('home');
        $baseLinkClasses = 'group relative block px-3 py-2 text-sm font-semibold tracking-[0.01em] transition-all duration-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#7FA08F]/40';
        $inactiveLinkClasses = 'text-slate-700 hover:text-[#23413C]';
        $activeLinkClasses = 'text-[#23413C]';
        $homeSectionItems = [
            ['label' => 'Home', 'target' => 'home'],
            ['label' => 'Struktur', 'target' => 'struktur'],
            ['label' => 'Data Santri', 'target' => 'data-santri'],
            ['label' => 'Kurikulum', 'target' => 'kurikulum'],
            ['label' => 'Sign Up', 'target' => 'signup'],
        ];
        $routeItems = [
            [
                'label' => 'Pengumuman',
                'url' => route('pengumuman'),
                'active' => request()->routeIs('pengumuman'),
            ],
            [
                'label' => 'Pendaftaran',
                'url' => route('pendaftaran'),
                'active' => request()->routeIs('pendaftaran'),
            ],
        ];
    @endphp
    <div class="pointer-events-none absolute inset-x-0 top-0 h-8 bg-[#334B49]"></div>
    <div class="max-w-screen-xl relative z-10 flex flex-wrap items-center justify-between mx-auto pt-8 pb-1">
        <a href="/" class="flex items-center space-x-3 rtl:space-x-reverse">
            <img src="{{ asset('images/assets/logo3.png') }}" class="h-14 hidden sm:block lg:h-16" alt="Logo" />
        </a>

        <button data-collapse-toggle="navbar-default" type="button"
            class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200"
            aria-controls="navbar-default" aria-expanded="false">
            <span class="sr-only">Open main menu</span>
            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M1 1h15M1 7h15M1 13h15" />
            </svg>
        </button>

        <div class="hidden w-full md:block md:w-auto" id="navbar-default">
            <ul class="mt-4 flex flex-col rounded-2xl border border-[#D9E6DE] bg-white/95 p-3 shadow-[0_18px_44px_-30px_rgba(28,50,45,0.45)]
                 md:mt-0 md:flex-row md:items-center md:gap-2 md:rounded-none md:border-0 md:bg-transparent md:p-0 md:shadow-none rtl:space-x-reverse">
                @foreach ($homeSectionItems as $item)
                    @php
                        $target = $item['target'];
                        $href = $isHome ? "#{$target}" : "{$homeUrl}#{$target}";
                        $isInitialActive = $isHome && $target === 'home';
                        $classes = $baseLinkClasses . ' ' . ($isInitialActive ? $activeLinkClasses : $inactiveLinkClasses);
                    @endphp
                    <li>
                        <a href="{{ $href }}"
                           class="{{ $classes }}"
                           data-nav-link
                           @if($isHome) data-scroll-target="{{ $target }}" @endif
                           @if($isInitialActive) aria-current="page" @endif>
                            <span>{{ $item['label'] }}</span>
                            <span aria-hidden="true"
                                class="pointer-events-none absolute inset-x-3 -bottom-0.5 h-0.5 origin-left scale-x-0 rounded-full bg-[#23413C] transition-transform duration-200 group-hover:scale-x-100 group-[aria-current=page]:scale-x-100"></span>
                        </a>
                    </li>
                @endforeach
                @foreach ($routeItems as $item)
                    @php
                        $classes = $baseLinkClasses . ' ' . ($item['active'] ? $activeLinkClasses : $inactiveLinkClasses);
                    @endphp
                    <li>
                        <a href="{{ $item['url'] }}"
                           class="{{ $classes }}"
                           data-nav-link
                           @if($item['active']) aria-current="page" @endif>
                            <span>{{ $item['label'] }}</span>
                            <span aria-hidden="true"
                                class="pointer-events-none absolute inset-x-3 -bottom-0.5 h-0.5 origin-left scale-x-0 rounded-full bg-[#23413C] transition-transform duration-200 group-hover:scale-x-100 group-[aria-current=page]:scale-x-100"></span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</nav>

@if ($isHome)
    @once
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const activeClasses = ['text-[#23413C]'];
                const inactiveClasses = ['text-slate-700', 'hover:text-[#23413C]'];
                const navLinks = Array.from(document.querySelectorAll('[data-scroll-target]'));

                if (!navLinks.length || !('IntersectionObserver' in window)) {
                    return;
                }

                const sections = navLinks
                    .map((link) => {
                        const sectionId = link.getAttribute('data-scroll-target');

                        return {
                            link,
                            section: document.getElementById(sectionId),
                        };
                    })
                    .filter((item) => item.section);

                if (!sections.length) {
                    return;
                }

                const setActiveLink = (activeId) => {
                    sections.forEach(({ link, section }) => {
                        const isActive = section.id === activeId;

                        link.classList.toggle(activeClasses[0], isActive);
                        link.classList.toggle(inactiveClasses[0], !isActive);
                        link.classList.toggle(inactiveClasses[1], !isActive);

                        if (isActive) {
                            link.setAttribute('aria-current', 'page');
                        } else {
                            link.removeAttribute('aria-current');
                        }
                    });
                };

                const observer = new IntersectionObserver((entries) => {
                    const visibleEntries = entries
                        .filter((entry) => entry.isIntersecting)
                        .sort((firstEntry, secondEntry) => secondEntry.intersectionRatio - firstEntry.intersectionRatio);

                    if (visibleEntries.length) {
                        setActiveLink(visibleEntries[0].target.id);
                    }
                }, {
                    rootMargin: '-30% 0px -45% 0px',
                    threshold: [0.2, 0.35, 0.5, 0.65],
                });

                sections.forEach(({ section }) => observer.observe(section));
            });
        </script>
    @endonce
@endif
