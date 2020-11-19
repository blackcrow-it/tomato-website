<?php

namespace App\Http\Controllers\Frontend;

use App\Category;
use App\Constants\ObjectType;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request, $slug)
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
        return view('frontend.category.post', [
            'category' => $category,
            'list' => get_posts($category->id, null, true),
            'featured_posts' => get_posts($category->id, 'category-top-news'),
            'breadcrumb' => Category::ancestorsOf($category->id)
        ]);
    }

    private function indexForCourse(Request $request, Category $category)
    {
        $list = get_courses($category->id, null, true, function ($query) use ($request) {
            if ($request->input('filter.level') !== null) {
                $query->where(function ($q) use ($request) {
                    $q->where('courses.level', $request->input('filter.level'))
                        ->orWhereNull('courses.level');
                });
            }

            if ($request->input('filter.promotion') !== null) {
                switch ($request->input('filter.promotion')) {
                    case 'discount':
                        $query->whereNotNull('courses.original_price');
                        break;

                    case 'free':
                        $query->where(function ($q) {
                            $q->whereNull('courses.price')
                                ->orWhere('courses.price', 0);
                        });
                        break;
                }
            }

            if ($request->input('filter.lesson_count') !== null) {
                $query->where('lesson_count.__lesson_count', '<=', $request->input('filter.lesson_count'));
            }
        });

        return view('frontend.category.course', [
            'category' => $category,
            'list' => $list,
            'breadcrumb' => Category::ancestorsOf($category->id),
        ]);
    }

    private function indexForBook(Category $category)
    {
        return view('frontend.category.book', [
            'category' => $category,
            'list' => get_books($category->id, null, true),
            'breadcrumb' => Category::ancestorsOf($category->id)
        ]);
    }
}
