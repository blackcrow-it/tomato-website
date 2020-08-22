<?php

use App\Category;
use App\Constants\ObjectType;
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
    function get_posts($category_id = null, $position = null, $paginate = false)
    {
        $query = (new PostRepo())
            ->getByFilterQuery([
                'category_id' => $category_id,
                'position' => $position
            ])
            ->where('posts.enabled', true);

        $list = $paginate ? $query->paginate(config('template.paginate.list.' . ObjectType::POST)) : $query->get();

        $categories = Category::where('enabled', true)
            ->whereIn('id', $list->pluck('category_id'))
            ->get();

        $mapFunction = function ($item) use ($categories) {
            $item->category = $categories->firstWhere('id', $item->category_id);
            $item->url = route('post', ['slug' => $item->slug]);
            $item->created_at = Carbon::parse($item->created_at);
            $item->updated_at = Carbon::parse($item->created_at);
            return $item;
        };

        if ($paginate) {
            $list->getCollection()->transform($mapFunction);
        } else {
            $list->transform($mapFunction);
        }

        return $list;
    }
}

if (!function_exists('get_courses')) {
    function get_courses($category_id = null, $position = null, $paginate = false)
    {
        $query = (new CourseRepo())
            ->getByFilterQuery([
                'category_id' => $category_id,
                'position' => $position
            ])
            ->where('courses.enabled', true);

        $list = $paginate ? $query->paginate(config('template.paginate.list.' . ObjectType::COURSE)) : $query->get();

        $categories = Category::where('enabled', true)
            ->whereIn('id', $list->pluck('category_id'))
            ->get();

        $mapFunction = function ($item) use ($categories) {
            $item->category = $categories->firstWhere('id', $item->category_id);
            $item->url = route('course', ['slug' => $item->slug]);
            $item->created_at = Carbon::parse($item->created_at);
            $item->updated_at = Carbon::parse($item->created_at);
            return $item;
        };

        if ($paginate) {
            $list->getCollection()->transform($mapFunction);
        } else {
            $list->transform($mapFunction);
        }

        return $list;
    }
}

if (!function_exists('get_categories')) {
    function get_categories($parent_id = null, $position = null)
    {
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

if (!function_exists('get_youtube_id_from_url')) {
    function get_youtube_id_from_url($url)
    {
        preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match);
        return $match[1] ?? null;
    }
}
