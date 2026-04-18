<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LowStockRequest;
use App\Http\Resources\InventorySummaryResource;
use App\Http\Resources\LowStockResource;
use App\Services\InventorySummaryService;
use App\Services\LowStockService;

class InventoryController extends Controller
{
    public function summary(InventorySummaryService $service)
    {
        $summary = $service->getSummary();

        return response()->json([
            'data' => InventorySummaryResource::collection($summary),
        ]);
    }

    public function lowStock(LowStockRequest $request, LowStockService $service)
    {
        $threshold = $request->validated()['threshold'] ?? 10;
        $items = $service->getLowStock($threshold);

        return response()->json([
            'data' => LowStockResource::collection($items),
        ]);
    }
}