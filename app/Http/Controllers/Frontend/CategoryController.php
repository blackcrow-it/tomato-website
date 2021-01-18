<?php

namespace App\Http\Controllers\Frontend;

use App\Book;
use App\Category;
use App\Constants\ObjectType;
use App\Course;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
            'breadcrumb' => Category::ancestorsOf($category->id),
            'featured_books' => Book::orderBy('created_at', 'desc')->take(5)->get()
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
            ->orderBy('updated_at', 'DESC');

        $list = $query->paginate(config('template.paginate.list.course'));

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
