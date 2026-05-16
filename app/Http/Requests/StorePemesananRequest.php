<?php

namespace App\Http\Requests;

use App\Support\PemesananCatalog;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class StorePemesananRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        $materialKeys = array_keys(PemesananCatalog::materials());
        $sizes = PemesananCatalog::sizes();

        return [
            'nama' => ['required', 'string', 'max:255'],
            'nama_kos' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'materials' => ['required', 'array', 'min:1'],
            'materials.*' => ['string', Rule::in($materialKeys)],
            'seragam_ppm_size' => ['nullable', 'string', Rule::in($sizes)],
            'baju_asad_size' => ['nullable', 'string', Rule::in($sizes)],
            'bukti_pembayaran' => [
                'required',
                File::types(['jpg', 'jpeg', 'png', 'pdf'])->max(2 * 1024),
            ],
        ];
    }
}
