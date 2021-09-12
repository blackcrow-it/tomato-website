<?php

namespace App\Http\Controllers\Frontend;

use App\Cart;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Setting;
use App\Category;
use App\ComboCourseRelatedBook;
use App\ComboCourses;
use App\ComboRelatedCombo;
use App\Constants\ObjectType;
use App\UserComboCourse;
use Illuminate\Database\Eloquent\Builder;
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

        $relatedCombosCourse = ComboRelatedCombo::query()
            ->with([
                'related_combo_course',
            ])
            ->whereHas('related_combo_course', function (Builder $query) {
                $query->where('enabled', true);
            })
            ->where('combo_course_id', $combo_course->id)
            ->orderBy('id', 'asc')
            ->get()
            ->pluck('related_combo_course');

        $isUserOwnedThisCourse = auth()->check()
            ? UserComboCourse::query()
            ->where('user_id', auth()->id())
            ->where('combo_course_id', $combo_course->id)
            ->exists()
            : false;

        $addedToCart = !$isUserOwnedThisCourse && auth()->check()
            ? Cart::query()
            ->where('user_id', auth()->id())
            ->where('type', ObjectType::COMBO_COURSE)
            ->where('object_id', $combo_course->id)
            ->exists()
            : false;

        $relatedBooks = ComboCourseRelatedBook::query()
            ->with('related_book')
            ->whereHas('related_book', function (Builder $query) {
                $query->where('enabled', true);
            })
            ->where('combo_course_id', $combo_course->id)
            ->orderBy('id', 'asc')
            ->get()
            ->pluck('related_book');

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
            'added_to_cart' => $addedToCart,
            'is_owned' => $isUserOwnedThisCourse,
            'related_combos_course' => $relatedCombosCourse,
            'related_books' => $relatedBooks,
            'status' => $status,
            'is_trial' => $isTrial,
            'consultation_background' => $consultationFormBg->value,
        ]);
    }
}
