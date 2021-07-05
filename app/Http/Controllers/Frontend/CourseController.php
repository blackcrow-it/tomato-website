<?php

namespace App\Http\Controllers\Frontend;

use App\Cart;
use App\Category;
use App\Constants\ObjectType;
use App\Course;
use App\CourseRelatedBook;
use App\CourseRelatedCourse;
use App\Http\Controllers\Controller;
use App\Part;
use App\UserCourse;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function all(Request $request) {
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

        if ($request->input('filter.level') !== null) {
            $query->where(function ($q) use ($request) {
                $q
                    ->where('courses.level', $request->input('filter.level'))
                    ->orWhereNull('courses.level');
            });
        }

        if ($request->input('filter.promotion') !== null) {
            switch ($request->input('filter.promotion')) {
                case 'discount':
                    $query->whereNotNull('original_price');
                    break;

                case 'free':
                    $query->where(function ($q) {
                        $q
                            ->whereNull('price')
                            ->orWhere('price', 0);
                    });
                    break;
            }
        }

        if ($request->input('filter.lesson_count') !== null) {
            $query->has('lessons', '<=', $request->input('filter.lesson_count'));
        }

        $query->orderBy('created_at', 'desc');

        $list = $query->paginate(config('template.paginate.list.course'));

        $category = new Category();
        $category->title = 'Tất cả khóa học';
        $category->link = route('course.all');

        return view('frontend.category.course', [
            'category' => $category,
            'list' => $list,
            'breadcrumb' => [],
        ]);
    }

    public function index(Request $request, $slug, $id)
    {
        $status = $request->input('status');
        $isTrial = false;
        $course = Course::with('teacher')
            ->firstWhere([
                'slug' => $slug,
                'enabled' => true
            ]);

        if ($course == null) {
            return redirect()->route('home');
        }

        $lessons = $course->lessons()
            ->where('enabled', true)
            ->orderByRaw('CASE WHEN order_in_course > 0 THEN 0 ELSE 1 END, order_in_course ASC')
            ->orderBy('title', 'asc')
            ->get();

        $lessons->map(function ($lesson) {
            $lesson->parts = $lesson->parts()
                ->orderByRaw('CASE WHEN order_in_lesson > 0 THEN 0 ELSE 1 END, order_in_lesson ASC')
                ->orderBy('created_at', 'asc')
                ->get();
        });
        foreach ($lessons as $lesson) {
            foreach ($lesson->parts as $p) {
                if($p->enabled_trial) {
                    $isTrial = true;
                    break;
                }
            }
        }

        $relatedCourses = CourseRelatedCourse::query()
            ->with([
                'related_course',
                'related_course.teacher'
            ])
            ->whereHas('related_course', function (Builder $query) {
                $query->where('enabled', true);
            })
            ->where('course_id', $course->id)
            ->orderBy('id', 'asc')
            ->get()
            ->pluck('related_course');

        $relatedBooks = CourseRelatedBook::query()
            ->with('related_book')
            ->whereHas('related_book', function (Builder $query) {
                $query->where('enabled', true);
            })
            ->where('course_id', $course->id)
            ->orderBy('id', 'asc')
            ->get()
            ->pluck('related_book');

        $isUserOwnedThisCourse = auth()->check()
            ? UserCourse::query()
            ->where('user_id', auth()->id())
            ->where('course_id', $course->id)
            ->where(function ($query) {
                $query->orWhere('expires_on', '>', now());
                $query->orWhereNull('expires_on');
            })
            ->exists()
            : false;

        $addedToCart = !$isUserOwnedThisCourse && auth()->check()
            ? Cart::query()
            ->where('user_id', auth()->id())
            ->where('type', ObjectType::COURSE)
            ->where('object_id', $course->id)
            ->exists()
            : false;

        return view('frontend.course.detail', [
            'course' => $course,
            'lessons' => $lessons,
            'breadcrumb' => Category::ancestorsAndSelf($course->category_id),
            'added_to_cart' => $addedToCart,
            'is_owned' => $isUserOwnedThisCourse,
            'related_courses' => $relatedCourses,
            'related_books' => $relatedBooks,
            'status' => $status,
            'is_trial' => $isTrial
        ]);
    }

    public function start($id)
    {
        $course = Course::where('enabled', true)->find($id);
        if ($course == null) {
            return redirect()->route('home');
        }

        $isOwned = UserCourse::where('user_id', auth()->user()->id)
            ->where('course_id', $course->id)
            ->where(function ($query) {
                $query->orWhere('expires_on', '>', now());
                $query->orWhereNull('expires_on');
            })
            ->exists();

        // if (!$isOwned) {
        //     return redirect()->to($course->url)->withErrors('Bạn chưa sở hữu khóa học này.');
        // }

        $lesson = $course->lessons()
            ->where('enabled', true)
            ->orderByRaw('CASE WHEN order_in_course > 0 THEN 0 ELSE 1 END, order_in_course ASC')
            ->orderBy('title', 'asc')
            ->first();
        if ($lesson == null) return redirect()->route('home');

        // Kiểm tra nếu không sở hữu thì chọn từ bài học được cho xem thử
        if (!$isOwned) {
            $part = $lesson->parts()
                ->where('enabled', true)
                ->where('enabled_trial', true)
                ->orderByRaw('CASE WHEN order_in_lesson > 0 THEN 0 ELSE 1 END, order_in_lesson ASC')
                ->orderBy('created_at', 'asc')
                ->first();
        } else {
            $part = $lesson->parts()
                ->where('enabled', true)
                ->orderByRaw('CASE WHEN order_in_lesson > 0 THEN 0 ELSE 1 END, order_in_lesson ASC')
                ->orderBy('created_at', 'asc')
                ->first();
        }
        if ($part == null) return redirect()->route('home');

        return redirect()->route('part', ['id' => $part->id]);
    }
}
