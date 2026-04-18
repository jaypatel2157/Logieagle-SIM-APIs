<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LowStockRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'threshold' => ['nullable', 'integer', 'min:0', 'max:1000000'],
        ];
    }
}