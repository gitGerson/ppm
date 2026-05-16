@extends('layouts.app')

@section('content')
    @php
        $selectedMaterials = old('materials', []);
        $selectedMaterials = is_array($selectedMaterials) ? $selectedMaterials : [];
        $formatRupiah = fn (int $amount): string => 'Rp '.number_format($amount, 0, ',', '.');
        $inputClass = 'w-full rounded-md border border-white/70 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm outline-none transition focus:border-[#315B54] focus:ring-2 focus:ring-white/60';
        $labelClass = 'text-sm font-semibold text-white';
    @endphp

    <div class="bg-[#f2f2f2] px-4 py-10 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-7xl">
            <p class="mb-3 text-sm text-slate-400">Form Pemesanan Materi</p>

            <section class="overflow-hidden bg-white shadow-[0_24px_80px_-55px_rgba(15,23,42,0.45)]">
                <div class="h-8 bg-[#2f524d]"></div>

                <div class="px-6 py-8 sm:px-10 lg:px-16">
                    <div class="mb-10 flex flex-wrap items-center justify-between gap-6">
                        <a href="{{ route('home') }}" class="inline-flex items-center">
                            <img src="{{ asset('images/assets/logo3.png') }}" alt="PPM Al-Kautsar logo" class="h-16 w-auto">
                        </a>
                    </div>

                    @if (session('status'))
                        <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-6 rounded-xl border border-rose-200 bg-rose-50 px-4 py-4 text-sm text-rose-700">
                            <div class="font-semibold">Periksa kembali data berikut:</div>
                            <ul class="mt-2 list-disc space-y-1 pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="grid gap-8 lg:grid-cols-[20rem_1fr]">
                        <aside class="space-y-6 text-[#315B54]">
                            <div class="rounded-lg border border-[#315B54] bg-white/85 p-4">
                                <h2 class="mb-3 text-base font-bold">Daftar Harga :</h2>
                                <ol class="list-decimal space-y-1 pl-5 text-sm">
                                    @foreach ($materials as $material)
                                        <li class="flex justify-between gap-4">
                                            <span>{{ $material['name'] }}</span>
                                            <span>{{ number_format($material['price'], 2, ',', '.') }}</span>
                                        </li>
                                    @endforeach
                                </ol>
                            </div>

                            <div class="rounded-lg border border-[#315B54] bg-white/85 p-4 text-sm leading-6">
                                <h2 class="mb-3 text-base font-bold">Pembayaran :</h2>
                                <div class="grid grid-cols-[3.5rem_1fr] gap-x-2">
                                    <span class="font-semibold">BRI</span>
                                    <span>0134 0101 8596 531 (Ariqa Candra)</span>
                                    <span class="font-semibold">DANA</span>
                                    <span>0812 2618 6652 (Maisatun Sabila)</span>
                                </div>
                            </div>

                            <div class="rounded-lg border border-[#315B54] bg-white/85 p-4 text-sm leading-5">
                                <h2 class="mb-3 text-base font-bold">NB :</h2>
                                <ol class="list-decimal space-y-2 pl-5">
                                    <li>Bagi santri yang belum pernah mondok sama sekali dianjurkan untuk membeli.</li>
                                    <li>Bagi santri yang sudah pernah mondok tidak harus ganti Al-Qur'an & semua materi kelas yang baru dengan syarat bisa dibaca dengan jelas.</li>
                                </ol>
                            </div>
                        </aside>

                        <form method="POST" action="{{ route('pemesanan.store') }}" enctype="multipart/form-data"
                            class="relative overflow-hidden rounded-[2rem_0_0_0] bg-[#9BB9B3] px-6 py-8 text-white sm:px-10"
                            data-pemesanan-form>
                            @csrf

                            <div class="pointer-events-none absolute inset-0 opacity-30"
                                style="background-image: url('{{ asset('images/assets/bg_asset.png') }}'); background-size: 34rem; background-position: left bottom; background-repeat: repeat;">
                            </div>

                            <div class="relative">
                                <nav class="mb-4 text-sm text-white/80">
                                    <ol class="flex items-center gap-3">
                                        <li>Materi</li>
                                        <li aria-hidden="true">›</li>
                                        <li>Form</li>
                                    </ol>
                                </nav>

                                <h1 class="text-3xl font-bold tracking-tight">Form Pemesanan Materi</h1>
                                <div class="my-6 h-px bg-white/80"></div>

                                <div class="grid gap-8 lg:grid-cols-[1fr_22rem]">
                                    <div class="space-y-5">
                                        <div>
                                            <label for="nama" class="{{ $labelClass }}">Nama Lengkap</label>
                                            <input id="nama" name="nama" type="text" value="{{ old('nama', auth()->user()->name) }}"
                                                required placeholder="Enter username" class="mt-2 {{ $inputClass }}">
                                        </div>

                                        <div>
                                            <label for="nama_kos" class="{{ $labelClass }}">Nama Kos</label>
                                            <input id="nama_kos" name="nama_kos" type="text" value="{{ old('nama_kos') }}"
                                                required placeholder="Kos Basecamp Putri" class="mt-2 {{ $inputClass }}">
                                        </div>

                                        <div>
                                            <label for="address" class="{{ $labelClass }}">Alamat Rumah</label>
                                            <input id="address" name="address" type="text" value="{{ old('address') }}"
                                                required placeholder="Sumampir" class="mt-2 {{ $inputClass }}">
                                            <p class="mt-2 text-xs text-white/80">Apabila warga setempat</p>
                                        </div>

                                        <fieldset>
                                            <legend class="{{ $labelClass }}">Materi</legend>
                                            <div class="mt-3 space-y-3">
                                                @foreach ($materials as $key => $material)
                                                    <label class="flex items-center gap-3 text-sm text-white">
                                                        <input type="checkbox" name="materials[]" value="{{ $key }}"
                                                            data-material-price="{{ $material['price'] }}"
                                                            @checked(in_array($key, $selectedMaterials, true))
                                                            class="size-4 rounded border-white/80 bg-white/20 text-[#315B54] focus:ring-white/70">
                                                        <span>{{ $material['name'] }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </fieldset>

                                        <div>
                                            <label for="total_amount_display" class="{{ $labelClass }}">Jumlah Pembayaran</label>
                                            <input id="total_amount_display" type="text" value="{{ $formatRupiah(0) }}" readonly
                                                class="mt-2 {{ $inputClass }}" data-total-display>
                                        </div>
                                    </div>

                                    <div class="space-y-5">
                                        <div>
                                            <h2 class="{{ $labelClass }}">Seragam PPM</h2>
                                            <div class="mt-2 border-4 border-[#1597d3] bg-white p-3 text-[0.58rem] text-slate-700">
                                                <div class="mb-2 text-center font-bold">PEREMPUAN</div>
                                                <div class="grid grid-cols-6 border border-slate-400 text-center">
                                                    @foreach (['SIZE', 'S', 'M', 'L', 'XL', 'XXL'] as $heading)
                                                        <div class="border border-slate-300 p-1 font-semibold">{{ $heading }}</div>
                                                    @endforeach
                                                    @foreach (['Lingkar dada', 'Pundak', 'Panjang tangan', 'Panjang baju'] as $row)
                                                        <div class="border border-slate-300 p-1 text-left">{{ $row }}</div>
                                                        @foreach (['104', '108', '112', '116', '120'] as $value)
                                                            <div class="border border-slate-300 p-1">{{ $value }}</div>
                                                        @endforeach
                                                    @endforeach
                                                </div>
                                                <div class="mb-2 mt-4 text-center font-bold">LAKI LAKI</div>
                                                <div class="grid grid-cols-6 border border-slate-400 text-center">
                                                    @foreach (['SIZE', 'S', 'M', 'L', 'XL', 'XXL'] as $heading)
                                                        <div class="border border-slate-300 p-1 font-semibold">{{ $heading }}</div>
                                                    @endforeach
                                                    @foreach (['Lingkar dada', 'Pundak', 'Panjang tangan', 'Panjang baju'] as $row)
                                                        <div class="border border-slate-300 p-1 text-left">{{ $row }}</div>
                                                        @foreach (['98', '102', '106', '110', '114'] as $value)
                                                            <div class="border border-slate-300 p-1">{{ $value }}</div>
                                                        @endforeach
                                                    @endforeach
                                                </div>
                                            </div>
                                            <p class="mt-1 text-xs text-white/90">*Size seragam PPM saja</p>
                                            <select name="seragam_ppm_size" class="mt-2 {{ $inputClass }}">
                                                <option value="">Ukuran</option>
                                                @foreach ($sizes as $size)
                                                    <option value="{{ $size }}" @selected(old('seragam_ppm_size') === $size)>{{ $size }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div>
                                            <label for="baju_asad_size" class="{{ $labelClass }}">Baju ASAD</label>
                                            <select id="baju_asad_size" name="baju_asad_size" class="mt-2 {{ $inputClass }}">
                                                <option value="">Ukuran</option>
                                                @foreach ($sizes as $size)
                                                    <option value="{{ $size }}" @selected(old('baju_asad_size') === $size)>{{ $size }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div>
                                            <label for="bukti_pembayaran" class="{{ $labelClass }}">Unggah Bukti Pembayaran</label>
                                            <input id="bukti_pembayaran" name="bukti_pembayaran" type="file" required
                                                accept=".jpg,.jpeg,.png,.pdf"
                                                class="mt-2 block w-full text-sm text-white file:mr-4 file:rounded file:border file:border-white/70 file:bg-white/10 file:px-4 file:py-2 file:text-sm file:font-medium file:text-white hover:file:bg-white/20">
                                        </div>

                                        <div class="pt-6 text-right">
                                            <button type="submit"
                                                class="rounded-lg bg-[#315B54] px-8 py-3 text-sm font-bold uppercase text-white transition hover:bg-[#24443f] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white/70">
                                                Kirim
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.querySelector('[data-pemesanan-form]');

            if (!form) {
                return;
            }

            const totalDisplay = form.querySelector('[data-total-display]');
            const checkboxes = Array.from(form.querySelectorAll('[data-material-price]'));
            const formatter = new Intl.NumberFormat('id-ID');

            const updateTotal = () => {
                const total = checkboxes
                    .filter((checkbox) => checkbox.checked)
                    .reduce((sum, checkbox) => sum + Number(checkbox.dataset.materialPrice || 0), 0);

                totalDisplay.value = `Rp ${formatter.format(total)}`;
            };

            checkboxes.forEach((checkbox) => checkbox.addEventListener('change', updateTotal));
            updateTotal();
        });
    </script>
@endsection
