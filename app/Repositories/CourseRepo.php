<?php

namespace App\Repositories;

use App\Category;
use DB;

class CourseRepo
{
    public function getByFilterQuery($filter)
    {
        $query = DB::table('courses')
            ->leftJoin('users as author', 'courses.created_by', '=', 'author.id')
            ->leftJoin('users as editor', 'courses.updated_by', '=', 'editor.id')
            ->select([
                'courses.*',
                'author.username as __created_by',
                'editor.username as __updated_by'
            ]);

        if (!empty($filter)) {
            if ($filter['position'] ?? false) {
                $query
                    ->join('course_position', 'course_position.course_id', '=', 'courses.id')
                    ->where('course_position.code', $filter['position'])
                    ->addSelect('course_position.order_in_position as __order_in_position')
                    ->orderByRaw('CASE WHEN course_position.order_in_position > 0 THEN 0 ELSE 1 END, course_position.order_in_position ASC');
            }

            if ($filter['category_id'] ?? false) {
                $categoryIds = Category::descendantsAndSelf($filter['category_id'])->pluck('id');
                $query
                    ->whereIn('category_id', $categoryIds)
                    ->orderByRaw('CASE WHEN order_in_category > 0 THEN 0 ELSE 1 END, order_in_category ASC');
            }
        }

        $query->orderBy('created_at', 'DESC');

        return $query;
    }
}
