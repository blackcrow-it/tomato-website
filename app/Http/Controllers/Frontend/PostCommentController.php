<?php

namespace App\Http\Controllers\Frontend;

use App\ChildPostComment;
use App\Http\Controllers\Controller;
use App\PostComment;
use Illuminate\Http\Request;
use Auth;
use App\Post;

class PostCommentController extends Controller
{
    public function createPostComment(Request $request, $id)
    {
        $postComment = new PostComment();
        $postComment->content = $request->content_cmt;
        $postComment->post_id = $id;
        $user = Auth::user()->pluck('id')->toArray();
        $user_id = array_shift($user);
        $postComment->user_id = $user_id;
        $postComment->save();
        return back();
    }

    public function deletePostComment($id)
    {
        $postComment = PostComment::find($id);
        $postComment->childPostComments()->delete();
        $postComment->delete();
        return back();
    }
}
