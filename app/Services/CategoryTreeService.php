<?php

namespace App\Services;

use App\Models\Category;
use App\Support\CacheKeys;
use Illuminate\Support\Facades\Cache;

class CategoryTreeService
{
    public function getTree(): array
    {
        return Cache::remember(CacheKeys::CATEGORY_TREE, now()->addHour(), function () {
            $categories = Category::orderBy('name')->get([
                'id', 'parent_id', 'name', 'slug', 'is_active'
            ]);

            $byId = $categories->keyBy('id');

            $activeNodes = [];

            foreach ($categories as $category) {
                if (!$category->is_active) {
                    continue;
                }

                $activeNodes[$category->id] = [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'parent_id' => $category->parent_id,
                    'children' => [],
                ];
            }

            $tree = [];

            foreach ($activeNodes as $id => &$node) {
                $parentId = $node['parent_id'];

                while ($parentId && (!isset($activeNodes[$parentId]))) {
                    $parentId = optional($byId->get($parentId))->parent_id;
                }

                if ($parentId && isset($activeNodes[$parentId])) {
                    $activeNodes[$parentId]['children'][] = &$node;
                } else {
                    $tree[] = &$node;
                }
            }

            return $tree;
        });
    }

    public function forgetCache(): void
    {
        Cache::forget(CacheKeys::CATEGORY_TREE);
    }
}