<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        Category::truncate();

        $electronics = Category::create([
            'name' => 'Electronics',
            'slug' => Str::slug('Electronics'),
            'parent_id' => null,
            'is_active' => true,
        ]);

        $mobiles = Category::create([
            'name' => 'Mobile Phones',
            'slug' => Str::slug('Mobile Phones'),
            'parent_id' => $electronics->id,
            'is_active' => true,
        ]);

        Category::create([
            'name' => 'Android Phones',
            'slug' => Str::slug('Android Phones'),
            'parent_id' => $mobiles->id,
            'is_active' => true,
        ]);

        Category::create([
            'name' => 'iPhones',
            'slug' => Str::slug('iPhones'),
            'parent_id' => $mobiles->id,
            'is_active' => true,
        ]);

        Category::create([
            'name' => 'Laptops',
            'slug' => Str::slug('Laptops'),
            'parent_id' => $electronics->id,
            'is_active' => true,
        ]);

        $home = Category::create([
            'name' => 'Home Appliances',
            'slug' => Str::slug('Home Appliances'),
            'parent_id' => null,
            'is_active' => true,
        ]);

        Category::create([
            'name' => 'Kitchen Appliances',
            'slug' => Str::slug('Kitchen Appliances'),
            'parent_id' => $home->id,
            'is_active' => true,
        ]);

        Category::create([
            'name' => 'Cleaning Appliances',
            'slug' => Str::slug('Cleaning Appliances'),
            'parent_id' => $home->id,
            'is_active' => true,
        ]);

        $fashion = Category::create([
            'name' => 'Fashion',
            'slug' => Str::slug('Fashion'),
            'parent_id' => null,
            'is_active' => true,
        ]);

        Category::create([
            'name' => 'Men Fashion',
            'slug' => Str::slug('Men Fashion'),
            'parent_id' => $fashion->id,
            'is_active' => true,
        ]);

        Category::create([
            'name' => 'Women Fashion',
            'slug' => Str::slug('Women Fashion'),
            'parent_id' => $fashion->id,
            'is_active' => true,
        ]);

        // Edge case: inactive parent with active child
        $inactiveRoot = Category::create([
            'name' => 'Archived Category',
            'slug' => Str::slug('Archived Category'),
            'parent_id' => null,
            'is_active' => false,
        ]);

        Category::create([
            'name' => 'Recovered Active Child',
            'slug' => Str::slug('Recovered Active Child'),
            'parent_id' => $inactiveRoot->id,
            'is_active' => true,
        ]);

        
        Category::create([
            'name' => 'Books',
            'slug' => Str::slug('Books'),
            'parent_id' => null,
            'is_active' => true,
        ]);
    }
}