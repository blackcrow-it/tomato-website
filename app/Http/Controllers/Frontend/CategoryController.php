<?php

namespace App\Http\Controllers\Frontend;

use App\Category;
use App\Constants\ObjectType;
use App\Course;
use App\Http\Controllers\Controller;
use App\Post;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index($slug)
    {
        $category = Category::where('slug', $slug)
            ->firstOrFail();

        switch ($category->type) {
            case ObjectType::COURSE:
                return $this->indexForCourse($category);
                break;

            default:
                return $this->indexForPost($category);
                break;
        }
    }

    private function indexForPost(Category $category)
    {
        $categoryIds = $category
            ->descendants()
            ->pluck('id');

        $categoryIds[] = $category->id;

        $list = Post::where('enabled', true)
            ->whereIn('category_id', $categoryIds)
            ->orderByRaw('CASE WHEN order_in_category > 0 THEN 0 ELSE 1 END, order_in_category ASC, updated_at DESC')
            ->get();

        return view('frontend.category.post', [
            'category' => $category,
            'list' => $list
        ]);
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

        return view('frontend.category.course', [
            'category' => $category,
            'list' => $list
        ]);
    }
}
