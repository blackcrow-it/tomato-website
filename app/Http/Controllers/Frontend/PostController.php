<?php

namespace App\Http\Controllers\Frontend;

use App\Book;
use App\Category;
use App\Http\Controllers\Controller;
use App\Post;
use App\PostRelatedCourse;
use App\PostRelatedPost;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request, $slug, $id)
    {
        $post = Post::firstWhere([
            'slug' => $slug,
            'enabled' => true
        ]);

        if ($post == null) {
            return redirect()->route('home');
        }

        $relatedPosts = PostRelatedPost::with('related_post')
            ->whereHas('related_post', function (Builder $query) {
                $query->where('enabled', true);
            })
            ->where('post_id', $post->id)
            ->orderBy('id', 'asc')
            ->get()
            ->pluck('related_post');

        $relatedCourses = PostRelatedCourse::with([
            'related_course',
            'related_course.teacher'
        ])
            ->whereHas('related_course', function (Builder $query) {
                $query->where('enabled', true);
            })
            ->where('post_id', $post->id)
            ->orderBy('id', 'asc')
            ->get()
            ->pluck('related_course');

        $nextPost = Post::where('enabled', true)
            ->where('created_at', '>', $post->created_at)
            ->orderBy('created_at', 'asc')
            ->first();

        $prevPost = Post::where('enabled', true)
            ->where('created_at', '<', $post->created_at)
            ->orderBy('created_at', 'desc')
            ->first();

        return view('frontend.post.detail', [
            'post' => $post,
            'breadcrumb' => Category::ancestorsAndSelf($post->category_id),
            'related_posts' => $relatedPosts,
            'related_courses' => $relatedCourses,
            'featured_books' => Book::orderBy('created_at', 'desc')->take(5)->get(),
            'next_post' => $nextPost,
            'prev_post' => $prevPost,
        ]);
    }
}
