<?php

namespace App\Http\Controllers\Frontend;

use App\Category;
use App\Constants\ObjectType;
use App\Course;
use App\Http\Controllers\Controller;
use App\Post;
use App\Repositories\CourseRepo;
use App\Repositories\PostRepo;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    private $courseRepo;
    private $postRepo;

    public function __construct(CourseRepo $courseRepo, PostRepo $postRepo)
    {
        $this->courseRepo = $courseRepo;
        $this->postRepo = $postRepo;
    }

    public function index($slug)
    {
        $category = Category::where('slug', $slug)
            ->where('enabled', true)
            ->first();

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
            'list' => $this->postRepo->getByFilterQuery([
                'category_id' => $category->id
            ])->paginate()
        ]);
    }

    private function indexForCourse(Category $category)
    {
        return view('frontend.category.course', [
            'category' => $category,
            'list' => $this->courseRepo->getByFilterQuery([
                'category_id' => $category->id
            ])->paginate()
        ]);
    }
}
