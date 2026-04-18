<?php

namespace App\Services;

use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;

class MovementHistoryService
{
    public function getHistory(int $productId, array $filters): array
    {
        $perPage = $filters['per_page'] ?? 20;

        $baseQuery = StockMovement::query()
            ->where('product_id', $productId);

        if (!empty($filters['date_from'])) {
            $baseQuery->whereDate('moved_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $baseQuery->whereDate('moved_at', '<=', $filters['date_to']);
        }

        $summaryQuery = DB::table('stock_movements')
            ->where('product_id', $productId);

        if (!empty($filters['date_from'])) {
            $summaryQuery->whereDate('moved_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $summaryQuery->whereDate('moved_at', '<=', $filters['date_to']);
        }

        $summary = $summaryQuery->selectRaw("
            COALESCE(SUM(CASE WHEN movement_type = 'stock_in' THEN quantity ELSE 0 END), 0) as total_units_in,
            COALESCE(SUM(CASE WHEN movement_type IN ('stock_out', 'reservation') THEN quantity ELSE 0 END), 0) as total_units_out,
            COALESCE(SUM(CASE
                WHEN movement_type = 'stock_in' THEN quantity
                WHEN movement_type IN ('stock_out', 'reservation') THEN -quantity
                WHEN movement_type = 'reservation_release' THEN quantity
                ELSE 0
            END), 0) as net_movement
        ")->first();

        $movements = $baseQuery
            ->with('warehouse')
            ->orderByDesc('moved_at')
            ->paginate($perPage);

        return [
            'summary' => $summary,
            'movements' => $movements,
        ];
    }
}