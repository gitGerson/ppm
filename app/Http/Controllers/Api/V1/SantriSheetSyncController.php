<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ListSantriForSheetRequest;
use App\Http\Requests\UpdateSantriFromSheetRequest;
use App\Http\Resources\SantriSheetResource;
use App\Models\DetailSantri;
use App\Support\SantriSheetApiSync;
use App\Support\SantriSheetSchema;
use Illuminate\Http\JsonResponse;

class SantriSheetSyncController extends Controller
{
    public function index(ListSantriForSheetRequest $request): JsonResponse
    {
        $rows = DetailSantri::query()
            ->select(array_keys(SantriSheetSchema::columns()))
            ->orderBy('id')
            ->cursorPaginate((int) $request->validated('per_page', 100));

        return response()->json([
            'data' => SantriSheetResource::collection($rows->getCollection())->resolve($request),
            'schema' => SantriSheetSchema::columns(),
            'next_cursor' => $rows->nextCursor()?->encode(),
        ]);
    }

    public function update(UpdateSantriFromSheetRequest $request, int $santri, SantriSheetApiSync $sync): JsonResponse
    {
        $result = $sync->update($santri, $request->validated('base_revision'), $request->validated('changes'));

        return response()->json([
            'data' => (new SantriSheetResource($result['santri']))->resolve($request),
            'message' => $result['conflict'] ? 'Data berubah. Pilih versi database atau kirim ulang perubahan Anda.' : 'Tersimpan.',
        ], $result['conflict'] ? 409 : 200);
    }
}
