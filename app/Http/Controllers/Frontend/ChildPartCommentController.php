<?php

namespace App\Http\Controllers\Frontend;

use App\ChildPartComment;
use App\Http\Controllers\Controller;
use App\PartComment;
use Illuminate\Http\Request;
use Auth;

class ChildPartCommentController extends Controller
{
    public function createChildPartComment(Request $request)
    {
        $partcmt = PartComment::pluck('id')->toArray();
        $partcmt_id = array_shift($partcmt);
        $childPartComment = new ChildPartComment();
        $childPartComment->content = $request->content_cmt;
        $childPartComment->partcmt_id = $partcmt_id;
        $user = Auth::user()->pluck('id')->toArray();
        $user_id = array_shift($user);
        $childPartComment->user_id = $user_id;
        $childPartComment->save();
        return back();
    }

    public function deleteChildPartComment($id)
    {
        $childPartComment = ChildPartComment::find($id);
        $childPartComment->delete();
        return back();
    }
}
