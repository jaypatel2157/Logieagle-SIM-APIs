<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StockAdjustRequest;
use App\Services\StockAdjustmentService;

class StockController extends Controller
{
    public function adjust(StockAdjustRequest $request, StockAdjustmentService $service)
    {
        $result = $service->adjust($request->validated());

        return response()->json([
            'message' => 'Stock adjusted successfully.',
            'data' => $result,
        ]);
    }
}