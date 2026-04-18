<?php

namespace Database\Seeders;

use App\Models\Warehouse;
use Illuminate\Database\Seeder;

class WarehouseSeeder extends Seeder
{
    public function run(): void
    {
        Warehouse::truncate();

        Warehouse::insert([
            [
                'name' => 'Ahmedabad Central Warehouse',
                'city' => 'Ahmedabad',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Gandhinagar Distribution Hub',
                'city' => 'Gandhinagar',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Vadodara Stock Point',
                'city' => 'Vadodara',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}