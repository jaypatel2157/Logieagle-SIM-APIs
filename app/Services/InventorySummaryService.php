<?php

namespace App\Services;

use App\Support\CacheKeys;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class InventorySummaryService
{
    public function getSummary()
    {
        return Cache::remember(CacheKeys::INVENTORY_SUMMARY, now()->addMinutes(15), function () {
            return DB::table('products')
                ->leftJoin('stock', 'stock.product_id', '=', 'products.id')
                ->leftJoinSub(
                    DB::table('stock as s1')
                        ->select('s1.product_id', 'w.name as top_warehouse_name')
                        ->join('warehouses as w', 'w.id', '=', 's1.warehouse_id')
                        ->whereRaw('s1.quantity = (
                            SELECT MAX(s2.quantity)
                            FROM stock s2
                            WHERE s2.product_id = s1.product_id
                        )'),
                    'top_stock',
                    'top_stock.product_id',
                    '=',
                    'products.id'
                )
                ->selectRaw('
                    products.id as product_id,
                    products.name as product_name,
                    products.sku,
                    COALESCE(SUM(stock.quantity), 0) as total_quantity,
                    COALESCE(SUM(stock.reserved_quantity), 0) as total_reserved_quantity,
                    COALESCE(SUM(stock.quantity - stock.reserved_quantity), 0) as available_quantity,
                    MAX(top_stock.top_warehouse_name) as top_warehouse_name,
                    CASE WHEN COALESCE(SUM(stock.quantity), 0) = 0 THEN 1 ELSE 0 END as is_zero_stock
                ')
                ->groupBy('products.id', 'products.name', 'products.sku')
                ->orderBy('products.name')
                ->get();
        });
    }
}