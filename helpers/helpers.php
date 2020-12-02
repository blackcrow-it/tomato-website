<?php

use App\Category;
use App\Constants\ObjectType;
use App\Course;
use App\CoursePosition;
use App\Repositories\BookRepo;
use App\Repositories\CategoryRepo;
use App\Repositories\CourseRepo;
use App\Repositories\PostRepo;
use App\Teacher;
use Carbon\Carbon;
use Illuminate\Support\Str;

if (!function_exists('ddsql')) {
    function ddsql($query)
    {
        dd(Str::replaceArray("?", $query->getBindings(), str_replace('?', "'?'", $query->toSql())));
    }
}

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

        return number_format($money, 0, ',', '.') . ' ₫';
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

        if ($paginate === true) {
            $list = $query->paginate(config('template.paginate.list.' . ObjectType::POST));
        } else if ($paginate > 0) {
            $list = $query->paginate($paginate);
        } else {
            $list = $query->get();
        }

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
        $query = Course::query()
            ->with([
                'category' => function ($q) {
                    $q->where('enabled', true);
                },
                'teacher',
                'lessons' => function ($q) {
                    $q->where('enabled', true);
                },
                'author',
                'editor'
            ])
            ->where('enabled', true);

        if ($category_id) {
            $categoryIds = Category::descendantsAndSelf($category_id)->pluck('id');
            $query
                ->whereIn('category_id', $categoryIds)
                ->orderByRaw('CASE WHEN order_in_category > 0 THEN 0 ELSE 1 END, order_in_category ASC');
        }

        if ($position) {
            $coursePosition = CoursePosition::query()
                ->select([
                    'course_id',
                    'order_in_position'
                ])
                ->where('code', $position);

            $query
                ->joinSub($coursePosition, 'course_position', 'course_position.course_id', '=', 'courses.id')
                ->orderByRaw('CASE WHEN course_position.order_in_position > 0 THEN 0 ELSE 1 END, course_position.order_in_position asc');
        }

        $query->orderBy('courses.updated_at', 'desc');

        if ($paginate === true) {
            $list = $query->paginate(config('template.paginate.list.course'));
        } else if ($paginate > 0) {
            $list = $query->paginate($paginate);
        } else {
            $list = $query->get();
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
                $item->url = $item->link ?? route('category', ['slug' => $item->slug]);
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

if (!function_exists('get_books')) {
    function get_books($category_id = null, $position = null, $paginate = false)
    {
        $query = (new BookRepo())
            ->getByFilterQuery([
                'category_id' => $category_id,
                'position' => $position
            ])
            ->where('books.enabled', true);

        if ($paginate === true) {
            $list = $query->paginate(config('template.paginate.list.' . ObjectType::BOOK));
        } else if ($paginate > 0) {
            $list = $query->paginate($paginate);
        } else {
            $list = $query->get();
        }

        $categories = Category::where('enabled', true)
            ->whereIn('id', $list->pluck('category_id'))
            ->get();

        $mapFunction = function ($item) use ($categories) {
            $item->category = $categories->firstWhere('id', $item->category_id);
            $item->url = route('book', ['slug' => $item->slug]);
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

if (!function_exists('get_teachers')) {
    function get_teachers()
    {
        return Teacher::orderBy('name')->get();
    }
}
