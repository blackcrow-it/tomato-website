<?php

namespace App\Repositories;

use App\Category;
use DB;

class PostRepo
{
    public function getByFilterQuery($filter)
    {
        $query = DB::table('posts')
            ->leftJoin('users as author', 'posts.created_by', '=', 'author.id')
            ->leftJoin('users as editor', 'posts.updated_by', '=', 'editor.id')
            ->select([
                'posts.*',
                'author.username as __created_by',
                'editor.username as __updated_by'
            ]);

        if (!empty($filter)) {
            if ($filter['position'] ?? false) {
                $query
                    ->join('post_position', 'post_position.post_id', '=', 'posts.id')
                    ->where('post_position.code', $filter['position'])
                    ->addSelect('post_position.order_in_position as __order_in_position')
                    ->orderByRaw('CASE WHEN post_position.order_in_position > 0 THEN 0 ELSE 1 END, post_position.order_in_position ASC');
            }

            if ($filter['category_id'] ?? false) {
                $categoryIds = Category::descendantsAndSelf($filter['category_id'])->pluck('id');
                $query
                    ->whereIn('category_id', $categoryIds)
                    ->orderByRaw('CASE WHEN order_in_category > 0 THEN 0 ELSE 1 END, order_in_category ASC');
            }
        }

        $query->orderBy('updated_at', 'DESC');

        return $query;
    }
}
