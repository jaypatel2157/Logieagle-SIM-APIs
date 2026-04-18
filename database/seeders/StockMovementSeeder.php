<?php

namespace Database\Seeders;

use App\Enums\StockMovementType;
use App\Models\Stock;
use App\Models\StockMovement;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class StockMovementSeeder extends Seeder
{
    public function run(): void
    {
        StockMovement::truncate();

        $stocks = Stock::with(['product', 'warehouse'])->get();

        if ($stocks->isEmpty()) {
            return;
        }

        $movementTypes = [
            StockMovementType::STOCK_IN->value,
            StockMovementType::STOCK_OUT->value,
            StockMovementType::RESERVATION->value,
            StockMovementType::RESERVATION_RELEASE->value,
        ];

        $rows = [];

        for ($i = 1; $i <= 200; $i++) {
            $stock = $stocks->random();
            $movementType = $movementTypes[array_rand($movementTypes)];

            $maxQty = match ($movementType) {
                StockMovementType::STOCK_IN->value => 30,
                StockMovementType::STOCK_OUT->value => max(1, min(15, $stock->quantity)),
                StockMovementType::RESERVATION->value => max(1, min(10, max(1, $stock->quantity - $stock->reserved_quantity))),
                StockMovementType::RESERVATION_RELEASE->value => max(1, min(10, max(1, $stock->reserved_quantity ?: 1))),
                default => 5,
            };

            $quantity = rand(1, $maxQty);

            $rows[] = [
                'product_id' => $stock->product_id,
                'warehouse_id' => $stock->warehouse_id,
                'movement_type' => $movementType,
                'quantity' => $quantity,
                'reference_id' => 'REF-' . str_pad((string) $i, 5, '0', STR_PAD_LEFT),
                'reference_type' => fake()->randomElement(['order', 'purchase', 'manual_adjustment', 'transfer']),
                'note' => fake()->sentence(),
                'moved_at' => Carbon::now()->subDays(rand(0, 60))->subMinutes(rand(0, 1440)),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        StockMovement::insert($rows);
    }
}