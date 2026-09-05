<?php

namespace App\Http\Resources;

use App\Models\DetailSantri;
use App\Support\SantriSheetSchema;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin DetailSantri */
class SantriSheetResource extends JsonResource
{
    /** @return array{id: int, revision: string, values: array<string, mixed>} */
    public function toArray(Request $request): array
    {
        return [
            'id' => (int) $this->resource->getKey(),
            'revision' => SantriSheetSchema::revision($this->resource),
            'values' => SantriSheetSchema::values($this->resource),
        ];
    }
}
