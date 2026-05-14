@extends('layouts.app')

@section('content')
    <div class="bg-slate-100 py-12">
        <div class="mx-auto max-w-3xl px-4">
            <div class="mb-8">
                <nav class="mb-3 text-sm text-slate-600">
                    <ol class="flex items-center gap-2">
                        <li>
                            <a href="{{ route('home') }}" class="font-medium text-emerald-600 transition hover:text-emerald-700">Santri</a>
                        </li>
                        <li class="text-slate-400">/</li>
                        <li class="font-medium text-slate-700">Edit Profil</li>
                    </ol>
                </nav>
                <h1 class="text-3xl font-semibold text-slate-900">Edit Profil</h1>
                <p class="mt-2 text-sm text-slate-600">Perbarui nama dan email akun santri Anda.</p>
            </div>

            @if (session('status'))
                <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-4 text-sm text-rose-700">
                    <div class="font-semibold">Periksa kembali data berikut:</div>
                    <ul class="mt-2 list-disc space-y-1 pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('profile.update') }}" class="rounded-2xl border border-slate-200 bg-white p-6 shadow-[0_24px_70px_-42px_rgba(15,23,42,0.45)]">
                @csrf
                @method('PATCH')

                <div class="grid gap-5">
                    <div>
                        <label for="name" class="text-sm font-medium text-slate-700">Nama lengkap</label>
                        <input id="name" name="name" type="text" value="{{ old('name', auth()->user()->name) }}" required
                            autocomplete="name"
                            class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-800 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200">
                    </div>

                    <div>
                        <label for="email" class="text-sm font-medium text-slate-700">Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email', auth()->user()->email) }}" required
                            autocomplete="email"
                            class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-800 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200">
                    </div>
                </div>

                <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:items-center sm:justify-end">
                    <a href="{{ route('home') }}"
                        class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                        Batal
                    </a>
                    <button type="submit"
                        class="inline-flex items-center justify-center rounded-xl bg-[#23413C] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#182f2b] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#7FA08F]/50">
                        Simpan Profil
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
