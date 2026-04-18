<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Stock;
use App\Models\Warehouse;
use Illuminate\Database\Seeder;

class StockSeeder extends Seeder
{
    public function run(): void
    {
        Stock::truncate();

        $products = Product::all();
        $warehouses = Warehouse::all();

        foreach ($products as $index => $product) {
            // every 10th product has zero stock / no stock rows
            if (($index + 1) % 10 === 0) {
                continue;
            }

            // Some products in one warehouse, some in multiple warehouses
            $warehouseCount = (($index + 1) % 3 === 0) ? 3 : ((($index + 1) % 2 === 0) ? 2 : 1);
            $selectedWarehouses = $warehouses->random($warehouseCount);

            foreach ($selectedWarehouses as $warehouse) {
                $quantity = rand(0, 100);
                $reserved = $quantity > 0 ? rand(0, min(20, $quantity)) : 0;

                // some very low available stock
                if (($index + 1) % 7 === 0) {
                    $quantity = rand(5, 12);
                    $reserved = rand(3, min(10, $quantity));
                }

                Stock::create([
                    'product_id' => $product->id,
                    'warehouse_id' => $warehouse->id,
                    'quantity' => $quantity,
                    'reserved_quantity' => $reserved,
                ]);
            }
        }
    }
}