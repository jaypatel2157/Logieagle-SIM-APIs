<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InventorySummaryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'product_id' => $this->product_id,
            'product_name' => $this->product_name,
            'sku' => $this->sku,
            'total_quantity' => (int) $this->total_quantity,
            'total_reserved_quantity' => (int) $this->total_reserved_quantity,
            'available_quantity' => (int) $this->available_quantity,
            'top_warehouse_name' => $this->top_warehouse_name,
            'is_zero_stock' => (bool) $this->is_zero_stock,
        ];
    }
}