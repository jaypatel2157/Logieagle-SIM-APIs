<?php

namespace App\Services;

use App\Support\CacheKeys;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class LowStockService
{
    public function getLowStock(int $threshold = 10)
    {
        return Cache::remember(CacheKeys::lowStock($threshold), now()->addMinutes(10), function () use ($threshold) {
            return DB::table('stock')
                ->join('products', 'products.id', '=', 'stock.product_id')
                ->join('warehouses', 'warehouses.id', '=', 'stock.warehouse_id')
                ->selectRaw('
                    stock.product_id,
                    products.name as product_name,
                    products.sku,
                    stock.warehouse_id,
                    warehouses.name as warehouse_name,
                    stock.quantity,
                    stock.reserved_quantity,
                    (stock.quantity - stock.reserved_quantity) as available_quantity
                ')
                ->whereRaw('(stock.quantity - stock.reserved_quantity) < ?', [$threshold])
                ->orderBy('available_quantity')
                ->get();
        });
    }
}