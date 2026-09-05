<?php

namespace App\Support;

use App\Models\DetailSantri;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SantriSheetApiSync
{
    /**
     * @param  array<string, mixed>  $changes
     * @return array{santri: DetailSantri, conflict: bool}
     */
    public function update(int $id, string $baseRevision, array $changes): array
    {
        $result = DB::transaction(function () use ($id, $baseRevision, $changes): array {
            $santri = DetailSantri::query()->lockForUpdate()->findOrFail($id);
            $current = SantriSheetSchema::values($santri);
            $candidate = clone $santri;
            $candidate->fill($changes);
            $desired = SantriSheetSchema::values($candidate);

            if ($current === $desired) {
                return ['santri' => $santri, 'conflict' => false];
            }

            if (! hash_equals(SantriSheetSchema::revision($santri), $baseRevision)) {
                return ['santri' => $santri, 'conflict' => true];
            }

            $santri->fill($changes)->save();

            return ['santri' => $santri->refresh(), 'conflict' => false];
        });

        Log::info('DetailSantri spreadsheet update', [
            'detail_santri_id' => $id,
            'outcome' => $result['conflict'] ? 'conflict' : 'acknowledged',
        ]);

        return $result;
    }
}
