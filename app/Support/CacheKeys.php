<?php

namespace App\Support;

class CacheKeys
{
    public const CATEGORY_TREE = 'inventory:categories:tree';
    public const INVENTORY_SUMMARY = 'inventory:summary';

    public static function productList(array $filters): string
    {
        ksort($filters);
        return 'inventory:products:list:' . md5(json_encode($filters));
    }

    public static function lowStock(int $threshold): string
    {
        return "inventory:low-stock:threshold:{$threshold}";
    }
}