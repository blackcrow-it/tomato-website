<?php

namespace App\Http\Controllers\Frontend;

use App\ChildCourseComment;
use App\CourseComment;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;

class ChildCourseCommentController extends Controller
{
    public function createChildCourseComment(Request $request)
    {
        $coursecmt = CourseComment::pluck('id')->toArray();
        $coursecmt_id = array_shift($coursecmt);
        $childCourseComment = new ChildCourseComment();
        $childCourseComment->content = $request->content_cmt;
        $childCourseComment->coursecmt_id = $coursecmt_id;
        $user = Auth::user()->pluck('id')->toArray();
        $user_id = array_shift($user);
        $childCourseComment->user_id = $user_id;
        $childCourseComment->save();
        return back();
    }

    public function deleteChildCourseComment($id)
    {
        $childCourseComment = ChildCourseComment::find($id);
        $childCourseComment->delete();
        return back();
    }
}
