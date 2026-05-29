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
                'label' => 'Dashboard',
                'url' => route('home'),
                'active' => request()->routeIs('home'),
            ],
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
            [
                'label' => 'Guidebook',
                'url' => route('guidebook'),
                'active' => request()->routeIs('guidebook'),
            ],
            [
                'label' => 'Pemesanan',
                'url' => route('pemesanan.create'),
                'active' => request()->routeIs('pemesanan.*'),
            ],
        ];
        $authLinkClasses = 'inline-flex items-center justify-center rounded-full border border-[#23413C] px-4 py-2 text-sm font-semibold text-[#23413C] transition hover:bg-[#23413C] hover:text-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#7FA08F]/40';
        $accountMenuItemClasses = 'flex w-full items-center gap-3 px-4 py-2 text-left text-sm font-medium text-[#5D4D28] transition hover:bg-[#d8c895] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#7FA08F]/40';
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
                <!-- @foreach ($homeSectionItems as $item)
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
                @endforeach -->
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
                <li class="md:ml-2">
                    @auth
                        <div class="group relative">
                            <button type="button"
                                class="inline-flex size-10 items-center justify-center rounded-full text-[#1f1f1f] transition hover:bg-[#E4D8B3] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#7FA08F]/40"
                                aria-label="User menu">
                                <svg class="size-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15.75 7.5a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.25a7.5 7.5 0 0 1 15 0" />
                                </svg>
                            </button>

                            <div
                                class="invisible absolute right-0 top-full z-30 mt-2 w-48 overflow-hidden bg-[#E7D8A9]/95 opacity-0 shadow-[0_18px_40px_-24px_rgba(48,38,15,0.6)] ring-1 ring-[#B9A56E]/40 transition group-hover:visible group-hover:opacity-100 group-focus-within:visible group-focus-within:opacity-100">
                                <a href="{{ route('profile.edit') }}" class="{{ $accountMenuItemClasses }}">
                                    <svg class="size-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5s-3 1.34-3 3 1.34 3 3 3ZM8 11c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3ZM8 13c-2.33 0-7 1.17-7 3.5V19h14v-2.5C15 14.17 10.33 13 8 13ZM16 13c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5Z" />
                                    </svg>
                                    Edit Profil
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="{{ $accountMenuItemClasses }}">
                                        <svg class="size-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6A2.25 2.25 0 0 0 5.25 5.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M18 15l3-3m0 0-3-3m3 3H9" />
                                        </svg>
                                        LogOut
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="{{ $authLinkClasses }}">
                            Login Santri
                        </a>
                    @endauth
                </li>
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
