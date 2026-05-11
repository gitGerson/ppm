<nav class="relative bg-white border-gray-200">
    @php
        $isHome = request()->routeIs('home');
        $homeUrl = route('home');
        $baseLinkClasses = 'block py-2 px-3 text-black rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-black md:p-0';
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
    <div class="max-w-screen-xl relative flex flex-wrap items-center justify-between mx-auto py-2">
        <a href="/" class="flex items-center space-x-3 rtl:space-x-reverse">
            <img src="{{ asset('images/assets/logo3.png') }}" class="h-20 hidden sm:block lg:h-24" alt="Logo" />
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
            <ul class="font-medium flex flex-col p-4 md:p-0 mt-4 border border-gray-100 rounded-lg bg-gray-50
                 md:flex-row md:space-x-8 rtl:space-x-reverse md:mt-0 md:border-0 md:bg-transparent">
                @foreach ($homeSectionItems as $item)
                    @php
                        $target = $item['target'];
                        $href = $isHome ? "#{$target}" : "{$homeUrl}#{$target}";
                        $classes = $baseLinkClasses;
                        $isInitialActive = $isHome && $target === 'home';
                        if ($isInitialActive) {
                            $classes .= ' underline';
                        }
                    @endphp
                    <li>
                        <a href="{{ $href }}"
                           class="{{ $classes }}"
                           @if($isHome) data-scroll-target="{{ $target }}" @endif
                           @if($isInitialActive) aria-current="page" @endif>{{ $item['label'] }}</a>
                    </li>
                @endforeach
                @foreach ($routeItems as $item)
                    @php
                        $classes = $baseLinkClasses;
                        if ($item['active']) {
                            $classes .= ' underline';
                        }
                    @endphp
                    <li>
                        <a href="{{ $item['url'] }}"
                           class="{{ $classes }}"
                           @if($item['active']) aria-current="page" @endif>{{ $item['label'] }}</a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</nav>
