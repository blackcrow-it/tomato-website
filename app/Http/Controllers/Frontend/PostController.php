<?php

namespace App\Http\Controllers\Frontend;

use App\Category;
use App\Http\Controllers\Controller;
use App\Post;
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

        return view('frontend.post.detail', [
            'post' => $post,
            'breadcrumb' => Category::ancestorsAndSelf($post->category_id)
        ]);
    }
}
