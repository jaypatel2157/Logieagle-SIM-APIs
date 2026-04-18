<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LowStockResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'product_id' => $this->product_id,
            'product_name' => $this->product_name,
            'sku' => $this->sku,
            'warehouse_id' => $this->warehouse_id,
            'warehouse_name' => $this->warehouse_name,
            'quantity' => (int) $this->quantity,
            'reserved_quantity' => (int) $this->reserved_quantity,
            'available_quantity' => (int) $this->available_quantity,
        ];
    }
}