<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Product;
use App\Support\CacheKeys;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ProductListingService
{
    public function getPaginated(array $filters): LengthAwarePaginator
    {
        $perPage = $filters['per_page'] ?? 15;
        $page = request()->integer('page', 1);

        $cacheKey = CacheKeys::productList(array_merge($filters, ['page' => $page]));

        return Cache::remember($cacheKey, now()->addMinutes(15), function () use ($filters, $perPage) {
            $query = Product::query()
                ->active()
                ->with(['category'])
                ->leftJoin('stock', 'stock.product_id', '=', 'products.id')
                ->select([
                    'products.*',
                    DB::raw('COALESCE(SUM(stock.quantity - stock.reserved_quantity), 0) as available_stock')
                ])
                ->groupBy('products.id');

            if (!empty($filters['category_id'])) {
                $categoryIds = $this->resolveCategoryIds((int) $filters['category_id']);
                $query->whereIn('products.category_id', $categoryIds);
            }

            if (!empty($filters['warehouse_id'])) {
                $warehouseId = (int) $filters['warehouse_id'];
                $query->whereExists(function ($sub) use ($warehouseId) {
                    $sub->select(DB::raw(1))
                        ->from('stock as s2')
                        ->whereColumn('s2.product_id', 'products.id')
                        ->where('s2.warehouse_id', $warehouseId)
                        ->where('s2.quantity', '>', 0);
                });
            }

            if (!empty($filters['available_only'])) {
                $query->having('available_stock', '>', 0);
            }

            $query->search($filters['search'] ?? null)
                ->priceBetween($filters['min_price'] ?? null, $filters['max_price'] ?? null);

            $sortBy = $filters['sort_by'] ?? 'name';
            $direction = $filters['sort_direction'] ?? 'asc';

            match ($sortBy) {
                'price' => $query->orderBy('products.base_price', $direction),
                'available_stock' => $query->orderBy('available_stock', $direction),
                default => $query->orderBy('products.name', $direction),
            };

            return $query->paginate($perPage);
        });
    }

    private function resolveCategoryIds(int $rootCategoryId): array
    {
        $categories = Category::get(['id', 'parent_id']);
        $childrenMap = [];

        foreach ($categories as $category) {
            $childrenMap[$category->parent_id ?? 0][] = $category->id;
        }

        $ids = [];
        $stack = [$rootCategoryId];

        while ($stack) {
            $id = array_pop($stack);
            $ids[] = $id;

            foreach ($childrenMap[$id] ?? [] as $childId) {
                $stack[] = $childId;
            }
        }

        return array_values(array_unique($ids));
    }
}