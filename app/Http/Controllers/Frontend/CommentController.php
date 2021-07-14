<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Post;
use App\Part;
use App\Course;
use App\PostComment;
use Auth;

class CommentController extends Controller
{
    public function createComment(Request $request, $id)
    {
        $post = Post::find($id)->pluck('id')->toArray();
        $post_id = array_shift($post);
        $comment = new PostComment();
        $comment->content = $request->content_cmt;
        if ($comment->type = 'post') {
            $comment->type_id = $post_id;
        }
        $user = Auth::user()->pluck('id')->toArray();
        $user_id = array_shift($user);
        $comment->user_id = $user_id;
        $comment->save();
        return redirect("/");
    }

    public function deleteComment($id)
    {
        $comment = PostComment::find($id);
        if ($comment->type = 'post') {
            $comment->type_id = $id;
        }
        $comment->delete();
        return back();
    }
}
