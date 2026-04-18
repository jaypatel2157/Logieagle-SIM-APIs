    <?php

    namespace App\Services;

    use App\Enums\StockMovementType;
    use App\Models\Stock;
    use App\Models\StockMovement;
    use App\Support\CacheKeys;
    use Illuminate\Support\Facades\Cache;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Validation\ValidationException;

    class StockAdjustmentService
    {
        public function adjust(array $data): array
        {
            return DB::transaction(function () use ($data) {
                $movementType = StockMovementType::from($data['movement_type']);

                $stock = Stock::query()
                    ->where('product_id', $data['product_id'])
                    ->where('warehouse_id', $data['warehouse_id'])
                    ->lockForUpdate()
                    ->first();

                if (!$stock) {
                    $stock = Stock::create([
                        'product_id' => $data['product_id'],
                        'warehouse_id' => $data['warehouse_id'],
                        'quantity' => 0,
                        'reserved_quantity' => 0,
                    ]);

                    $stock = Stock::query()
                        ->whereKey($stock->id)
                        ->lockForUpdate()
                        ->first();
                }

                $quantity = (int) $data['quantity'];
                $available = $stock->quantity - $stock->reserved_quantity;

                switch ($movementType) {
                    case StockMovementType::STOCK_IN:
                        $stock->quantity += $quantity;
                        break;

                    case StockMovementType::STOCK_OUT:
                        if ($available < $quantity) {
                            throw ValidationException::withMessages([
                                'quantity' => 'Stock out rejected because available quantity would drop below zero.',
                            ]);
                        }
                        $stock->quantity -= $quantity;
                        break;

                    case StockMovementType::RESERVATION:
                        if ($available < $quantity) {
                            throw ValidationException::withMessages([
                                'quantity' => 'Reservation rejected because available quantity would drop below zero.',
                            ]);
                        }
                        $stock->reserved_quantity += $quantity;
                        break;

                    case StockMovementType::RESERVATION_RELEASE:
                        if ($stock->reserved_quantity < $quantity) {
                            throw ValidationException::withMessages([
                                'quantity' => 'Reservation release rejected because reserved quantity is insufficient.',
                            ]);
                        }
                        $stock->reserved_quantity -= $quantity;
                        break;
                }

                $stock->save();

                StockMovement::create([
                    'product_id' => $data['product_id'],
                    'warehouse_id' => $data['warehouse_id'],
                    'movement_type' => $movementType->value,
                    'quantity' => $quantity,
                    'reference_id' => $data['reference_id'] ?? null,
                    'reference_type' => $data['reference_type'] ?? null,
                    'note' => $data['note'] ?? null,
                    'moved_at' => $data['moved_at'] ?? now(),
                ]);

                $this->invalidateCaches();

                return [
                    'product_id' => $stock->product_id,
                    'warehouse_id' => $stock->warehouse_id,
                    'quantity' => $stock->quantity,
                    'reserved_quantity' => $stock->reserved_quantity,
                    'available_quantity' => $stock->quantity - $stock->reserved_quantity,
                ];
            });
        }

        private function invalidateCaches(): void
        {
            Cache::forget(CacheKeys::INVENTORY_SUMMARY);
            Cache::forget(CacheKeys::CATEGORY_TREE);
        }
    }