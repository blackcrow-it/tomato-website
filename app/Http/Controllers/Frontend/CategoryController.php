<?php

namespace App\Http\Controllers\Frontend;

use App\Category;
use App\Constants\ObjectType;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index($slug)
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
                return $this->indexForCourse($category);
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

    private function indexForCourse(Category $category)
    {
        return view('frontend.category.course', [
            'category' => $category,
            'list' => get_courses($category->id, null, true),
            'breadcrumb' => Category::ancestorsOf($category->id)
        ]);
    }
}
