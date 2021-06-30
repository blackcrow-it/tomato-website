<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Part;
use App\Teacher;
use Illuminate\Support\Facades\Mail;
use Auth;
use App\Mail\SendMailFromStudent;
use League\HTMLToMarkdown\HtmlConverter;

class PartContentController extends Controller
{
    // Gửi bài viết của học viên qua email cho giáo viên
    public function send(Request $request, $part_id) {
        $converter = new HtmlConverter();
        $part = Part::find($part_id);
        $lesson = $part->lesson;
        $course = $lesson->course;
        $teacher = Teacher::find($course->teacher_id);
        $data = [
            'email_student' => Auth::user()->email,
            'name_student' => Auth::user()->name,
            'phone_student' => Auth::user()->phone,
            'content' => $request->input('content'),
            'part' => $part,
            'part_content' => $converter->convert($part->part_content->content),
            'lesson' => $lesson,
            'course' => $course
        ];
        Mail::to($teacher->email)->send(new SendMailFromStudent($data));
        return true;
    }
}
