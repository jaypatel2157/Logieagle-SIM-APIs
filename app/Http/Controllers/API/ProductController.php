<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MovementHistoryRequest;
use App\Http\Requests\ProductIndexRequest;
use App\Http\Resources\ProductResource;
use App\Http\Resources\StockMovementResource;
use App\Services\MovementHistoryService;
use App\Services\ProductListingService;

class ProductController extends Controller
{
    public function index(ProductIndexRequest $request, ProductListingService $service)
    {
        $products = $service->getPaginated($request->validated());

        return ProductResource::collection($products);
    }

    public function movements(int $id, MovementHistoryRequest $request, MovementHistoryService $service)
    {
        $result = $service->getHistory($id, $request->validated());

        return response()->json([
            'summary' => [
                'total_units_in' => (int) $result['summary']->total_units_in,
                'total_units_out' => (int) $result['summary']->total_units_out,
                'net_movement' => (int) $result['summary']->net_movement,
            ],
            'data' => StockMovementResource::collection($result['movements']),
            'meta' => [
                'current_page' => $result['movements']->currentPage(),
                'last_page' => $result['movements']->lastPage(),
                'per_page' => $result['movements']->perPage(),
                'total' => $result['movements']->total(),
            ],
        ]);
    }
}