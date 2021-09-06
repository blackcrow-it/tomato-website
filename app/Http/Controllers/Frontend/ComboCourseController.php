<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Setting;
use App\Category;
use App\ComboCourses;
use Illuminate\Support\Facades\Auth;

class ComboCourseController extends Controller
{
    public function all(Request $request) {
        $category = new Category();
        $category->title = 'Tất cả combo khoá học';
        $category->link = route('book.all');
        $consultationFormBg = Setting::where('key', 'consultation_background')->first();

        $query = ComboCourses::query()
        ->with([
            // 'category' => function ($q) {
            //     $q->where('enabled', true);
            // },
            // 'courses' => function ($q) {
            //     $q->where('enabled', true);
            // },
        ])
        ->where('enabled', true);

        $query->orderBy('created_at', 'desc');

        $list = $query->paginate(config('template.paginate.list.course'));

        return view('frontend.category.combo_course', [
            'category' => $category,
            'list' => $list,
            'breadcrumb' => [],
            'consultation_background' => $consultationFormBg->value,
        ]);
    }

    public function index(Request $request, $slug, $id)
    {
        $status = $request->input('status');
        $isTrial = false;
        $combo_course = ComboCourses::firstWhere([
                'slug' => $slug,
                'enabled' => true
            ]);

        if ($combo_course == null) {
            return redirect()->route('home');
        }

        // $relatedCourses = CourseRelatedCourse::query()
        //     ->with([
        //         'related_course',
        //         'related_course.teacher'
        //     ])
        //     ->whereHas('related_course', function (Builder $query) {
        //         $query->where('enabled', true);
        //     })
        //     ->where('course_id', $course->id)
        //     ->orderBy('id', 'asc')
        //     ->get()
        //     ->pluck('related_course');

        // $isUserOwnedThisCourse = auth()->check()
        //     ? UserCourse::query()
        //     ->where('user_id', auth()->id())
        //     ->where('course_id', $course->id)
        //     ->where(function ($query) {
        //         $query->orWhere('expires_on', '>', now());
        //         $query->orWhereNull('expires_on');
        //     })
        //     ->exists()
        //     : false;

        // $addedToCart = !$isUserOwnedThisCourse && auth()->check()
        //     ? Cart::query()
        //     ->where('user_id', auth()->id())
        //     ->where('type', ObjectType::COURSE)
        //     ->where('object_id', $course->id)
        //     ->exists()
        //     : false;

        if (Auth::check()) {
            if (!Auth::user()->is_super_admin && !Auth::user()->roles()->exists()) {
                $combo_course->view++;
            }
        } else {
            $combo_course->view++;
        }
        $combo_course->save();

        $consultationFormBg = Setting::where('key', 'consultation_background')->first();
        return view('frontend.combo_course.detail', [
            'combo_course' => $combo_course,
            // 'breadcrumb' => Category::ancestorsAndSelf($course->category_id),
            'breadcrumb' => Category::ancestorsAndSelf($combo_course->category_id),
            // 'added_to_cart' => $addedToCart,
            // 'is_owned' => $isUserOwnedThisCourse,
            // 'related_courses' => $relatedCourses,
            // 'related_books' => $relatedBooks,
            'status' => $status,
            'is_trial' => $isTrial,
            'consultation_background' => $consultationFormBg->value,
        ]);
    }
}
