<?php

namespace App\Http\Controllers\Frontend;

use App\Category;
use App\Http\Controllers\Controller;
use App\Post;
use App\PostRelatedPost;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index($slug)
    {
        $post = Post::firstWhere([
            'slug' => $slug,
            'enabled' => true
        ]);

        if ($post == null) {
            return redirect()->route('home');
        }

        $relatedPosts = PostRelatedPost::with('related_post')
            ->wherehas('related_post', function (Builder $query) {
                $query->where('enabled', true);
            })
            ->where('post_id', $post->id)
            ->get()
            ->pluck('related_post');

        return view('frontend.post.detail', [
            'post' => $post,
            'breadcrumb' => Category::ancestorsAndSelf($post->category_id),
            'related_posts' => $relatedPosts
        ]);
    }
}
