<?php

namespace App\Repositories;

use App\Category;
use DB;

class SurveyRepo
{
    public function getByFilterQuery($filter)
    {
        $query = DB::table('surveys')
            ->select([
                'surveys.*',
            ]);

        // if (!empty($filter)) {
        //     if ($filter['position'] ?? false) {
        //         $query
        //             ->join('book_position', 'book_position.book_id', '=', 'books.id')
        //             ->where('book_position.code', $filter['position'])
        //             ->addSelect('book_position.order_in_position as __order_in_position')
        //             ->orderByRaw('CASE WHEN book_position.order_in_position > 0 THEN 0 ELSE 1 END, book_position.order_in_position ASC');
        //     }

        //     if ($filter['category_id'] ?? false) {
        //         $categoryIds = Category::descendantsAndSelf($filter['category_id'])->pluck('id');
        //         $query
        //             ->whereIn('category_id', $categoryIds)
        //             ->orderByRaw('CASE WHEN order_in_category > 0 THEN 0 ELSE 1 END, order_in_category ASC');
        //     }
        // }

        $query->orderBy('created_at', 'DESC');

        return $query;
    }
}
