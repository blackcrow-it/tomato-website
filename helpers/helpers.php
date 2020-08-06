<?php

use App\Category;
use App\Repositories\CategoryRepo;
use App\Repositories\CourseRepo;
use App\Repositories\PostRepo;
use Carbon\Carbon;

if (!function_exists('categories_traverse')) {
    function categories_traverse($nodes, $prefix = '|--- ')
    {
        $traverse = function ($categories, $prefix) use (&$traverse) {
            $tree = collect();

            foreach ($categories as $category) {
                $category->title = ($prefix ? "$prefix" : '') . $category->title;

                $children = $traverse($category->children, $prefix . '|--- ');

                $tree->push($category);
                $tree = $tree->merge($children);
            }

            return $tree;
        };

        return $traverse($nodes, $prefix);
    }
}

if (!function_exists('currency')) {
    function currency($money, $default = 'Miễn phí')
    {
        $money = intval($money);

        if ($money == 0) return $default;

        return number_format($money, 0, '.', ' ') . 'đ';
    }
}

if (!function_exists('get_template_position')) {
    function get_template_position($type = null)
    {
        if ($type == null) {
            return config('template.position');
        }

        return array_filter(config('template.position'), function ($item) use ($type) {
            return $item['type'] == $type;
        });
    }
}

if (!function_exists('get_posts')) {
    function get_posts($category_id = null, $position = null)
    {
        $posts = (new PostRepo())
            ->getByFilterQuery([
                'category_id' => $category_id,
                'position' => $position
            ])
            ->where('posts.enabled', true)
            ->get();

        $categories = Category::where('enabled', true)
            ->whereIn('id', $posts->pluck('category_id'))
            ->get();

        return $posts->map(function ($item) use ($categories) {
            $item->category = $categories->firstWhere('id', $item->category_id);
            $item->url = route('post', ['slug' => $item->slug]);
            $item->created_at = Carbon::parse($item->created_at);
            $item->updated_at = Carbon::parse($item->created_at);
            return $item;
        });
    }
}

if (!function_exists('get_courses')) {
    function get_courses($category_id = null, $position = null)
    {
        $courses = (new CourseRepo())
            ->getByFilterQuery([
                'category_id' => $category_id,
                'position' => $position
            ])
            ->where('courses.enabled', true)
            ->get();

        $categories = Category::where('enabled', true)
            ->whereIn('id', $courses->pluck('category_id'))
            ->get();

        return $courses->map(function ($item) use ($categories) {
            $item->category = $categories->firstWhere('id', $item->category_id);
            $item->url = route('course', ['slug' => $item->slug]);
            $item->created_at = Carbon::parse($item->created_at);
            $item->updated_at = Carbon::parse($item->created_at);
            return $item;
        });
    }
}

if (!function_exists('get_categories')) {
    function get_categories($parent_id = null, $position = null)
    {
        $cacheKey = 'get_categories_' . $parent_id . '_' . $position;

        $data = (new CategoryRepo())
            ->getByFilterQuery([
                'parent_id' => $parent_id,
                'position' => $position
            ])
            ->where('categories.enabled', true)
            ->get()
            ->map(function ($item) {
                $item->url = $item->url ?? route('category', ['slug' => $item->slug]);
                return $item;
            });

        return $data;
    }
}
