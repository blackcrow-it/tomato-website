<?php

namespace App\Http\Controllers\Frontend;

use App\Category;
use App\Course;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index($slug)
    {
        $category = Category::where('slug', $slug)
            ->firstOrFail();

        switch ($category->type) {
            case Category::TYPE_COURSE:
                return $this->indexForCourse($category);
                break;

            default:
                return $this->indexForPost($category);
                break;
        }
    }

    private function indexForPost(Category $category)
    {
        return 'Nothing for post.';
    }

    private function indexForCourse(Category $category)
    {
        $categoryIds = $category
            ->descendants()
            ->pluck('id');

        $categoryIds[] = $category->id;

        $list = Course::where('enabled', true)
            ->whereIn('category_id', $categoryIds)
            ->orderByRaw('CASE WHEN order_in_category > 0 THEN 0 ELSE 1 END, order_in_category ASC, updated_at DESC')
            ->get();

        return view('frontend.category.index', [
            'category' => $category,
            'list' => $list
        ]);
    }
}
