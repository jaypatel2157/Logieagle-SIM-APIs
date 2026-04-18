<?php

namespace App\Http\Requests;

use App\Enums\StockMovementType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StockAdjustRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'warehouse_id' => ['required', 'integer', 'exists:warehouses,id'],
            'movement_type' => ['required', Rule::in(array_map(fn($case) => $case->value, StockMovementType::cases()))],
            'quantity' => ['required', 'integer', 'min:1'],
            'reference_id' => ['nullable', 'string', 'max:255'],
            'reference_type' => ['nullable', 'string', 'max:255'],
            'note' => ['nullable', 'string'],
            'moved_at' => ['nullable', 'date'],
        ];
    }
}