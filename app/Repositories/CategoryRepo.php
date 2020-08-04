<?php

namespace App\Repositories;

use App\Category;
use DB;

class CategoryRepo
{
    public function getByFilterQuery($filter)
    {
        $query = DB::table('categories')
            ->leftJoin('categories as subcategory', 'subcategory.id', '=', 'categories.parent_id')
            ->select([
                'categories.id',
                'categories.icon',
                'categories.title',
                'categories.type',
                'categories.enabled',
                'categories.parent_id',
                'categories.slug',
                DB::raw('COUNT(subcategory.id) as __subcategory_count')
            ])
            ->groupBy([
                'categories.id',
                'categories.icon',
                'categories.title',
                'categories.type',
                'categories.enabled',
                'categories.parent_id',
                'categories.slug',
            ]);

        if (!empty($filter)) {
            if ($filter['parent_id'] ?? false) {
                $query->where('categories.parent_id', $filter['parent_id']);
            }

            if ($filter['position'] ?? false) {
                $query
                    ->join('category_position', 'category_position.category_id', '=', 'categories.id')
                    ->where('category_position.code', $filter['position'])
                    ->addSelect('category_position.order_in_position as __order_in_position')
                    ->orderByRaw('CASE WHEN category_position.order_in_position > 0 THEN 0 ELSE 1 END, category_position.order_in_position ASC')
                    ->groupBy('category_position.order_in_position');
            }
        }

        $query->orderBy('title', 'ASC');

        return $query;
    }
}
