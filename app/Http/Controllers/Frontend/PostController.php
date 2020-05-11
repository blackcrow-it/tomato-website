<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index($slug)
    {
        $post = Post::where('slug', $slug)->firstOrFail();

        return view('frontend.post.detail', [
            'post' => $post
        ]);
    }
}
