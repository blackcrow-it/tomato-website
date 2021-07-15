<?php

namespace App\Http\Controllers\Frontend;

use App\ChildPartComment;
use App\Http\Controllers\Controller;
use App\PartComment;
use Illuminate\Http\Request;
use Auth;
use App\Part;

class PartCommentController extends Controller
{
    public function createPostComment(Request $request, $id)
    {
        $partComment = new PartComment();
        $partComment->content = $request->content_cmt;
        $partComment->part_id = $id;
        $user = Auth::user()->pluck('id')->toArray();
        $user_id = array_shift($user);
        $partComment->user_id = $user_id;
        $partComment->save();
        return back();
    }

    public function deletePostComment($id)
    {
        $partComment = PartComment::find($id);
        $partComment->childPartComments()->delete();
        $partComment->delete();
        return back();
    }
}
