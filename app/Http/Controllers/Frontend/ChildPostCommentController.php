<?php

namespace App\Http\Controllers\Frontend;

use App\ChildPostComment;
use App\Http\Controllers\Controller;
use App\PostComment;
use Illuminate\Http\Request;
use Auth;

class ChildPostCommentController extends Controller
{
    public function createChildPostComment(Request $request)
    {
        $postcmt = PostComment::pluck('id')->toArray();
        $postcmt_id = array_shift($postcmt);
        $childPostComment = new ChildPostComment();
        $childPostComment->content = $request->content_cmt;
        $childPostComment->postcmt_id = $postcmt_id;
        $user = Auth::user()->pluck('id')->toArray();
        $user_id = array_shift($user);
        $childPostComment->user_id = $user_id;
        $childPostComment->save();
        return back();
    }

    public function deleteChildPostComment($id)
    {
        $childPostComment = ChildPostComment::find($id);
        $childPostComment->delete();
        return back();
    }
}
