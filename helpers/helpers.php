<?php

use App\Category;
use App\Constants\ObjectType;
use App\Course;
use App\CoursePosition;
use App\Repositories\BookRepo;
use App\Repositories\CategoryRepo;
use App\Repositories\CourseRepo;
use App\Repositories\PostRepo;
use App\Repositories\PracticeTestRepo;
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
        try {
            $money = intval($money);

            if ($money == 0) return $default;

            return number_format($money, 0, ',', '.') . ' ₫';
        } catch (\Throwable $th) {
            return $default;
        }
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
            $item->url = route('post', ['slug' => $item->slug, 'id' => $item->id]);
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

        $query->orderBy('courses.created_at', 'desc');

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
                $item->url = $item->link ?? route('category', ['slug' => $item->slug, 'id' => $item->id]);
                return $item;
            });

        return $data;
    }
}

if (!function_exists('get_youtube_id_from_url')) {
    function get_youtube_id_from_url($url)
    {
        preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match);
        return $match[1] ?? $url;
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
            $item->url = route('book', ['slug' => $item->slug, 'id' => $item->id]);
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


// Hàm rút gọn ký tự
if (!function_exists('truncate')) {
    function truncate($text, $chars = 25)
    {
        if (strlen($text) <= $chars) {
            return $text;
        }
        $text = $text . " ";
        $text = substr($text, 0, $chars);
        $text = substr($text, 0, strrpos($text, ' '));
        $text = $text . "...";
        return $text;
    }
}

if (!function_exists('get_current_shift')) {
    function get_current_shift($shifts)
    {
       
        $now = strtotime('now');
        $result = array_filter($shifts, function($var) use ($now) {
            $start = strtotime($var->start_time);
            $end = strtotime($var->end_time);
            return $end >= $now;
         });
        dd($shifts);
        return $result;
    }
}


if (!function_exists('get_next_day')) {
    function get_next_day($shifts, $days_txt = null)
    {
        $end = 'null';
        $shift = get_current_shift($shifts);
        
        if($days_txt == null) {
            return null;
        }

        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $weekdays = [1,2,3,4,5,6,0];
        $days = explode(",", $days_txt);
        usort($days, function($a, $b) use($weekdays){
            return array_search($a, $weekdays) - array_search($b, $weekdays);
         });
        $now = (int)date('w');
        if ($now == 0) {
            if (time() < strtotime($end)) {
                if (in_array($now, $days)) {
                    return strtotime('now');
                }
            } else {
                $nums = array_values(array_filter($days, function ($value) use ($now) {
                    return $value != $now;
                }));
                
                if (isset($nums[0])){
                   return strtotime('next sunday +'. $nums[0]. 'days');
                }
            }
        } else {
            $nums = array_values(array_filter($days, function ($value) use ($now) {
                return $value >= $now || $value == 0;
            }));

            if (isset($nums[0])) {
                $curr = $nums[0];

                if($curr == 0 && count($nums) == 1){
                    return strtotime('next sunday');
                }

                if ($curr == $now){
                    if(time()<strtotime($end)){
                        return strtotime('now');
                    } else{
                        if(isset($nums[1])){
                            return strtotime('last sunday +'. $nums[1]. 'days');
                        }
                    }   
                } else {
                    return strtotime('last sunday +'. $curr. 'days');
                }
            }       
        }
        return  null;
    }
}

if (!function_exists('find_closest')) {
    function find_closest($array, $duration)
    {
        $result = array();
        $now = strtotime('now');
        
        foreach ($array as &$value) {
            $start = strtotime($value->start_time);
            $end = strtotime($value->end_time) + $duration*60;
            //dd(date('h:i',$now), date("h:i",  $end));
            if($end >= $now){
                array_push($result, $value);
            }
        };
        if(count($result)> 0){
            return reset($result);
        }

        return $array->first();
        //dd($result);
        // $result = array_filter($array, function($var) use ($now) {
        //     $start = strtotime($var->start_time);
        //     $end = strtotime($var->end_time);
        //     return $end >= $now;
        //  });
        //  dd($result);
    }
}

if (!function_exists('get_top_practice_test')) {
    function get_top_practice_test($id, $month, $year)
    {
        $ptRepo = new PracticeTestRepo();
        $pt = $ptRepo->getResultById($id);
        if($pt != null){
            Auth::user()->id = Auth::user()->id;
            $query = DB::select('select * from (select id, row_number() OVER (ORDER BY score desc) from practice_test_results where extract(month from "test_date") = :month and extract(year from "test_date") = :year and practice_test_id = :practice_test_id order by "score" asc) as temp where id = :id',
            ['id'=>$id, 'month'=> $month, 'year'=>$year,'practice_test_id'=>$pt->practice_test_id]);
            return $query;
        }
    }
}

