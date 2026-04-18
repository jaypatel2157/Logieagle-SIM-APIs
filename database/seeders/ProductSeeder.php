<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::truncate();

        $categoryIds = Category::where('name', '!=', 'Books')->pluck('id')->values()->all();

        $productNames = [
            'iPhone 15', 'Samsung Galaxy S24', 'OnePlus 12', 'Xiaomi Redmi Note 13',
            'Dell Inspiron 15', 'HP Pavilion 14', 'Lenovo ThinkPad E14', 'MacBook Air M2',
            'LG Microwave Oven', 'Philips Air Fryer', 'Prestige Mixer Grinder', 'Dyson Vacuum Cleaner',
            'Samsung Refrigerator', 'IFB Washing Machine', 'Sony Headphones', 'Boat Neckband',
            'Canon DSLR Camera', 'Nikon Mirrorless Camera', 'Asus Gaming Laptop', 'Acer Aspire 7',
            'Realme Narzo', 'Vivo V30', 'Oppo Reno', 'Puma T-Shirt',
            'Nike Running Shoes', 'Adidas Hoodie', 'Levis Jeans', 'Woodland Jacket',
            'Women Handbag', 'Casual Shirt', 'Formal Shirt', 'Bluetooth Speaker',
            'Smart Watch', 'Tablet Pro', 'Office Chair', 'Gaming Mouse',
            'Mechanical Keyboard', 'USB-C Charger', 'Power Bank', 'Smart LED TV',
            'Water Purifier', 'Ceiling Fan', 'Induction Cooktop', 'Rice Cooker',
            'Hair Dryer', 'Trimmer Kit', 'Fitness Band', 'Travel Backpack',
            'Wireless Earbuds', 'Laptop Stand'
        ];

        foreach ($productNames as $index => $name) {
            Product::create([
                'category_id' => $categoryIds[array_rand($categoryIds)],
                'name' => $name,
                'sku' => 'SKU-' . str_pad((string) ($index + 1), 4, '0', STR_PAD_LEFT),
                'description' => $name . ' description for inventory testing and filtering.',
                'base_price' => rand(500, 150000),
                'is_active' => true,
            ]);
        }
    }
}