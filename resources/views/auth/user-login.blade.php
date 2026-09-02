@php
    $activePanel = $errors->hasBag('register') || old('auth_mode') === 'register' ? 'register' : 'login';
    $inputClass = 'w-full border-0 bg-[#f3f3f3] px-4 py-3 text-xs text-slate-800 outline-none ring-0 placeholder:text-slate-400 focus:bg-white focus:ring-2 focus:ring-[#264d45]/20';
    $labelClass = 'text-xs font-medium text-black';
    $buttonClass = 'rounded-md bg-black px-5 py-2 text-xs font-bold uppercase text-white transition hover:bg-[#264d45] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-black/30';
    $ghostButtonClass = 'rounded-md border-2 border-white px-5 py-2 text-xs font-bold uppercase text-white transition hover:bg-white hover:text-[#264d45] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white/70';
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Santri</title>
    <link rel="icon" type="image/png" href="{{ asset('images/assets/logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/assets/logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/assets/logo.png') }}">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: "Inter", system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif;
        }
    </style>
</head>

<body class="min-h-screen bg-[#f2f2f2]">
    <main class="flex min-h-screen items-center px-4 py-6 sm:px-6 lg:px-8">
        <div class="mx-auto w-full max-w-7xl">
            <section
                class="relative overflow-hidden rounded-xl bg-white shadow-[0_24px_80px_-55px_rgba(15,23,42,0.45)]"
                data-auth-shell
                data-initial-panel="{{ $activePanel }}">
                <div
                    class="pointer-events-none absolute inset-0 opacity-55"
                    style="background-image: radial-gradient(circle at 1px 1px, rgba(38,77,69,0.12) 1px, transparent 0); background-size: 38px 38px;">
                </div>

                <div class="relative grid min-h-[36rem] grid-cols-1 lg:grid-cols-2">
                    <div class="relative z-10 flex items-center justify-center px-6 py-12 sm:px-12">
                        <form method="POST" action="{{ route('login.store') }}" class="w-full max-w-sm space-y-5 transition duration-500"
                            data-auth-form="login">
                            @csrf
                            <input type="hidden" name="auth_mode" value="login">

                            <div class="text-center">
                                <h1 class="text-3xl font-black uppercase tracking-[0.35em] text-black">Sign In</h1>
                                <p class="mt-4 text-sm text-slate-500">Masukan akun yang sudah terdaftar!</p>
                            </div>

                            @if (session('status'))
                                <div role="status" class="rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-xs text-emerald-700">
                                    {{ session('status') }}
                                </div>
                            @endif

                            @if ($errors->login->any() || ($errors->any() && ! $errors->hasBag('register')))
                                <div class="rounded-md border border-rose-200 bg-rose-50 px-4 py-3 text-xs text-rose-700">
                                    {{ $errors->login->first() ?: $errors->first() }}
                                </div>
                            @endif

                            <div class="space-y-2">
                                <label for="login-email" class="{{ $labelClass }}">email</label>
                                <input id="login-email" name="email" type="email" value="{{ old('auth_mode') === 'login' ? old('email') : '' }}" required
                                    autocomplete="email" placeholder="Masukan email yang terdaftar !"
                                    class="{{ $inputClass }}">
                            </div>

                            <div class="space-y-2">
                                <label for="login-password" class="{{ $labelClass }}">kata sandi</label>
                                <input id="login-password" name="password" type="password" required autocomplete="current-password"
                                    placeholder="Masukan kata sandi !" class="{{ $inputClass }}">
                            </div>

                            <div class="flex items-center justify-between gap-4">
                                <label class="flex items-center gap-2 text-xs text-slate-500">
                                    <input type="checkbox" name="remember" value="1"
                                        class="size-4 rounded border-slate-300 text-[#264d45] focus:ring-[#264d45]">
                                    Ingat saya
                                </label>
                                <button type="submit" class="{{ $buttonClass }}">Sign In</button>
                            </div>
                        </form>
                    </div>

                    <div class="relative z-10 flex items-center justify-center px-6 py-12 sm:px-12">
                        <form method="POST" action="{{ route('register.store') }}" class="w-full max-w-sm space-y-4 transition duration-500"
                            data-auth-form="register">
                            @csrf
                            <input type="hidden" name="auth_mode" value="register">

                            <div class="text-center">
                                <h2 class="text-3xl font-black uppercase tracking-[0.35em] text-black">Sign Up</h2>
                                <p class="mt-4 text-sm text-slate-500">Daftar Akun Baru</p>
                            </div>

                            @if ($errors->register->any())
                                <div class="rounded-md border border-rose-200 bg-rose-50 px-4 py-3 text-xs text-rose-700">
                                    {{ $errors->register->first() }}
                                </div>
                            @endif

                            <div class="space-y-2">
                                <label for="register-name" class="{{ $labelClass }}">nama lengkap</label>
                                <input id="register-name" name="name" type="text" value="{{ old('auth_mode') === 'register' ? old('name') : '' }}" required
                                    autocomplete="name" placeholder="Masukkan nama lengkap Anda."
                                    class="{{ $inputClass }}">
                            </div>

                            <div class="space-y-2">
                                <label for="register-email" class="{{ $labelClass }}">email</label>
                                <input id="register-email" name="email" type="email" value="{{ old('auth_mode') === 'register' ? old('email') : '' }}" required
                                    autocomplete="email" placeholder="Masukkan alamat email yang valid."
                                    class="{{ $inputClass }}">
                            </div>

                            <div class="space-y-2">
                                <label for="register-password" class="{{ $labelClass }}">kata sandi</label>
                                <input id="register-password" name="password" type="password" required autocomplete="new-password"
                                    placeholder="Buat kata sandi yang aman." class="{{ $inputClass }}">
                            </div>

                            <div class="space-y-2">
                                <label for="password-confirmation" class="{{ $labelClass }}">konfirmasi kata sandi</label>
                                <input id="password-confirmation" name="password_confirmation" type="password" required autocomplete="new-password"
                                    placeholder="Masukkan ulang kata sandi untuk konfirmasi." class="{{ $inputClass }}">
                            </div>

                            <div class="text-center">
                                <button type="submit" class="{{ $buttonClass }}">Sign Up</button>
                            </div>
                        </form>
                    </div>

                    <div class="relative z-20 p-3 lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2 lg:transition-transform lg:duration-700 lg:ease-out"
                        data-auth-panel-wrap>
                        <div
                            class="flex min-h-[22rem] size-full items-center justify-center rounded-[4rem_1.25rem_4rem_8rem] border border-white/30 bg-[linear-gradient(160deg,#1f6b66_0%,#25483b_48%,#c6a83b_100%)] px-8 py-12 text-center text-white shadow-inner transition-[border-radius] duration-700 ease-out lg:min-h-0"
                            data-auth-panel>
                            <div class="max-w-md">
                                <h2 class="text-2xl font-black tracking-[0.18em]" data-panel-heading>
                                    Welcome Back, Kauster!
                                </h2>
                                <p class="mt-6 text-sm leading-6" data-panel-copy>
                                    Sudah punya akun? Jika belum daftar sekarang.
                                </p>
                                <button type="button" class="mt-8 {{ $ghostButtonClass }}" data-auth-toggle>
                                    Sign Up
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const shell = document.querySelector('[data-auth-shell]');

            if (!shell) {
                return;
            }

            const panelWrap = shell.querySelector('[data-auth-panel-wrap]');
            const panel = shell.querySelector('[data-auth-panel]');
            const heading = shell.querySelector('[data-panel-heading]');
            const copy = shell.querySelector('[data-panel-copy]');
            const toggle = shell.querySelector('[data-auth-toggle]');
            const forms = {
                login: shell.querySelector('[data-auth-form="login"]'),
                register: shell.querySelector('[data-auth-form="register"]'),
            };

            const setPanel = (panelName) => {
                const isRegister = panelName === 'register';

                heading.textContent = isRegister ? 'Ahlan, Kauster !' : 'Welcome Back, Kauster!';
                copy.textContent = isRegister
                    ? 'Masuk dan mulai jelajahi berbagai kegiatan atau pengelolaan santri di Pondok Pesantren Mahasiswa.'
                    : 'Sudah punya akun? Jika belum daftar sekarang.';
                toggle.textContent = isRegister ? 'Sign In' : 'Sign Up';

                forms.login.classList.toggle('opacity-20', isRegister);
                forms.register.classList.toggle('opacity-20', !isRegister);
                panelWrap.classList.toggle('lg:-translate-x-full', isRegister);
                panel.classList.toggle('rounded-[1.25rem_8rem_4rem_4rem]', isRegister);
                panel.classList.toggle('rounded-[4rem_1.25rem_4rem_8rem]', !isRegister);
            };

            toggle.addEventListener('click', () => {
                const nextPanel = panelWrap.classList.contains('lg:-translate-x-full') ? 'login' : 'register';

                setPanel(nextPanel);
            });

            setPanel(shell.dataset.initialPanel || 'login');
        });
    </script>
</body>

</html>
