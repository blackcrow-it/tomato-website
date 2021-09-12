<?php

namespace App\Http\Controllers\Frontend;

use App\Book;
use App\Category;
use App\ComboCourses;
use App\Constants\ObjectType;
use App\Course;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Setting;

class CategoryController extends Controller
{
    public function index(Request $request, $slug, $id)
    {
        $category = Category::firstWhere([
            'slug' => $slug,
            'enabled' => true
        ]);

        if ($category == null) {
            return redirect()->route('home');
        }

        switch ($category->type) {
            case ObjectType::COURSE:
                return $this->indexForCourse($request, $category);
                break;

            case ObjectType::COMBO_COURSE:
                return $this->indexForComboCourse($request, $category);
                break;

            case ObjectType::BOOK:
                return $this->indexForBook($category);
                break;

            default:
                return $this->indexForPost($category);
                break;
        }
    }

    private function indexForPost(Category $category)
    {
        $consultationFormBg = Setting::where('key', 'consultation_background')->first();
        return view('frontend.category.post', [
            'category' => $category,
            'list' => get_posts($category->id, null, true),
            'breadcrumb' => Category::ancestorsOf($category->id),
            'featured_books' => Book::orderBy('created_at', 'desc')->take(5)->get(),
            'consultation_background' => $consultationFormBg->value,
        ]);
    }

    private function indexForCourse(Request $request, Category $category)
    {
        $categoryIds = Category::descendantsAndSelf($category->id)->pluck('id');

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
            ->where('enabled', true)
            ->whereIn('category_id', $categoryIds);

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

        $query
            ->orderByRaw('CASE WHEN order_in_category > 0 THEN 0 ELSE 1 END, order_in_category ASC')
            ->orderBy('created_at', 'desc');

        $list = $query->paginate(config('template.paginate.list.course'));

        $consultationFormBg = Setting::where('key', 'consultation_background')->first();
        error_log($consultationFormBg->value);
        return view('frontend.category.course', [
            'category' => $category,
            'list' => $list,
            'breadcrumb' => Category::ancestorsOf($category->id),
            'consultation_background' => $consultationFormBg->value,
        ]);
    }

    private function indexForComboCourse(Request $request, Category $category)
    {
        $categoryIds = Category::descendantsAndSelf($category->id)->pluck('id');

        $query = ComboCourses::query()
            ->with([
                'category' => function ($q) {
                    $q->where('enabled', true);
                },
            ])
            ->where('enabled', true)
            ->whereIn('category_id', $categoryIds);

        $query
            ->orderByRaw('CASE WHEN order_in_category > 0 THEN 0 ELSE 1 END, order_in_category ASC')
            ->orderBy('created_at', 'desc');

        $list = $query->paginate(config('template.paginate.list.combo_course'));

        $consultationFormBg = Setting::where('key', 'consultation_background')->first();
        error_log($consultationFormBg->value);
        return view('frontend.category.combo_course', [
            'category' => $category,
            'list' => $list,
            'breadcrumb' => Category::ancestorsOf($category->id),
            'consultation_background' => $consultationFormBg->value,
        ]);
    }

    private function indexForBook(Category $category)
    {
        $consultationFormBg = Setting::where('key', 'consultation_background')->first();
        return view('frontend.category.book', [
            'category' => $category,
            'list' => get_books($category->id, null, true),
            'breadcrumb' => Category::ancestorsOf($category->id),
            'consultation_background' => $consultationFormBg->value,
        ]);
    }
}
