<?php

use App\Models\Brand;
use App\Models\Item;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

if (!function_exists('fetchBrandsForItem'))
{
    function fetchBrandsForItem($item_name)
    {
        $brand = (new Brand())->getTable();
        $item = (new Item())->getTable();

        $select = [
            "{$brand}.brand_name",
            "{$brand}.id",
        ];

        $brands = Item::select($select)
            ->join($brand, "{$brand}.id", "=", "{$item}.brand_id")
            ->where('item_name', "$item_name")
            ->get();

        return $brands;
    }
}

if (!function_exists('fetchItemsForCategory'))
{
    function fetchItemsForCategory($category_id)
    {
        $category = (new Category())->getTable();
        $item = (new Item())->getTable();

        $select = [
            //"{$category}.category_name",
            //"{$category}.id",
            DB::raw('DISTINCT items.item_name'),
            'items.id'
        ];

        $items = Item::select($select)
            ->where('category_id', $category_id)
            ->groupBy('items.item_name')
            ->get();

        return $items;
    }
}

if (!function_exists('role')) {
    function role()
    {

    }
}
