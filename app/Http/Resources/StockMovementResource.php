<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StockMovementResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'movement_type' => $this->movement_type,
            'quantity' => $this->quantity,
            'reference_id' => $this->reference_id,
            'reference_type' => $this->reference_type,
            'note' => $this->note,
            'moved_at' => $this->moved_at?->toDateTimeString(),
            'warehouse' => [
                'id' => $this->warehouse?->id,
                'name' => $this->warehouse?->name,
            ],
        ];
    }
}