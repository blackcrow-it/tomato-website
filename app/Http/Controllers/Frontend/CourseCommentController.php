<?php

namespace App\Http\Controllers\Frontend;

use App\ChildCourseComment;
use App\CourseComment;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Course;

class CourseCommentController extends Controller
{
    public function createCourseComment(Request $request, $id)
    {
        $courseComment = new CourseComment();
        $courseComment->content = $request->content_cmt;
        $courseComment->course_id = $id;
        $user = Auth::user()->pluck('id')->toArray();
        $user_id = array_shift($user);
        $courseComment->user_id = $user_id;
        $courseComment->save();
        return back();
    }

    public function deleteCourseComment($id)
    {
        $courseComment = CourseComment::find($id);
        $courseComment->childCourseComments()->delete();
        $courseComment->delete();
        return back();
    }
}
