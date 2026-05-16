<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePemesananRequest;
use App\Models\Item;
use App\Models\Pemesanan;
use App\Support\PemesananCatalog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PemesananPageController extends Controller
{
    public function create(): View
    {
        return view('pages.pemesanan', [
            'materials' => PemesananCatalog::materials(),
            'sizes' => PemesananCatalog::sizes(),
        ]);
    }

    public function store(StorePemesananRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $selectedMaterials = $validated['materials'];
        $totalAmount = PemesananCatalog::totalFor($selectedMaterials);

        DB::transaction(function () use ($request, $validated, $selectedMaterials, $totalAmount): void {
            $proofPath = $request->file('bukti_pembayaran')->store('bukti-pembayaran', 'public');

            $pemesanan = Pemesanan::query()->create([
                'user_id' => $request->user()->id,
                'order_date' => now()->toDateString(),
                'nama' => $validated['nama'],
                'nama_kos' => $validated['nama_kos'],
                'address' => $validated['address'],
                'total_amount' => $totalAmount,
                'payment_status' => 'belum_lunas',
                'seragam_ppm_size' => $validated['seragam_ppm_size'] ?? null,
                'baju_asad_size' => $validated['baju_asad_size'] ?? null,
                'bukti_pembayaran_path' => $proofPath,
            ]);

            foreach ($selectedMaterials as $materialKey) {
                $material = PemesananCatalog::materials()[$materialKey];
                $item = Item::query()->firstOrCreate(
                    ['name' => $material['name']],
                    [
                        'price' => $material['price'],
                        'image_url' => null,
                        'size' => null,
                    ],
                );

                $pemesanan->detailPemesanans()->create([
                    'item_id' => $item->id,
                    'quantity' => 1,
                    'total_amount' => $material['price'],
                ]);
            }
        });

        return redirect()
            ->route('pemesanan.create')
            ->with('status', 'Pemesanan berhasil dikirim. Admin akan memeriksa bukti pembayaran Anda.');
    }
}
