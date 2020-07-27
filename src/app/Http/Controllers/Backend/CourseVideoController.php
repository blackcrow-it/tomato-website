<?php

namespace App\Http\Controllers\Backend;

use App\Course;
use App\CourseVideo;
use App\Http\Controllers\Controller;
use App\Http\Requests\CourseVideoRequest;
use App\Jobs\ConvertToHlsJob;
use Illuminate\Http\Request;

class CourseVideoController extends Controller
{
    public function list(Request $request)
    {
        $course = Course::find($request->input('course_id'));
        if ($course == null) {
            return redirect()->route('admin.course.list')->withErrors('Khóa học không tồn tại hoặc đã bị xóa.');
        }

        $list = CourseVideo::where('course_id', $course->id)
            ->orderByRaw('CASE WHEN order_in_course > 0 THEN 0 ELSE 1 END, order_in_course ASC, updated_at DESC')
            ->get();

        return view('backend.course_video.list', [
            'course' => $course,
            'list' => $list
        ]);
    }

    public function add(Request $request)
    {
        $course = Course::find($request->input('course_id'));
        if ($course == null) {
            return redirect()->route('admin.course.list')->withErrors('Khóa học không tồn tại hoặc đã bị xóa.');
        }

        return view('backend.course_video.edit', [
            'course' => $course
        ]);
    }

    public function submitAdd(CourseVideoRequest $request)
    {
        $course = Course::find($request->input('course_id'));
        if ($course == null) {
            return redirect()->route('admin.course.list')->withErrors('Khóa học không tồn tại hoặc đã bị xóa.');
        }

        $video = new CourseVideo();

        $this->processCourseVideoFromRequest($request, $video);

        return redirect()
            ->route('admin.course_video.list', ['course_id' => $video->course_id])
            ->with('success', 'Thêm video mới thành công.');
    }

    public function edit($id)
    {
        $video = CourseVideo::find($id);
        if ($video == null) {
            return redirect()->route('admin.course.list')->withErrors('Video không tồn tại hoặc đã bị xóa.');
        }

        return view('backend.course_video.edit', [
            'course' => $video->course,
            'data' => $video
        ]);
    }

    public function submitEdit(CourseVideoRequest $request, $id)
    {
        $video = CourseVideo::find($id);
        if ($video == null) {
            return redirect()->route('admin.course.list')->withErrors('Video không tồn tại hoặc đã bị xóa.');
        }

        $this->processCourseVideoFromRequest($request, $video);

        return redirect()
            ->route('admin.course_video.list', ['course_id' => $video->course_id])
            ->with('success', 'Thay đổi video thành công.');
    }

    private function processCourseVideoFromRequest(Request $request, CourseVideo $video)
    {
        $triggerJob = false;

        $data = $request->all();
        $video->fill($data);
        $video->save();
    }

    public function submitDelete($id)
    {
        $video = CourseVideo::find($id);
        if ($video == null) {
            return redirect()->route('admin.course.list')->withErrors('Video không tồn tại hoặc đã bị xóa.');
        }

        $video->delete();

        return redirect()
            ->route('admin.course_video.list', ['course_id' => $video->course_id])
            ->with('success', 'Xóa khóa học thành công.');
    }

    public function submitEnabled(Request $request)
    {
        $video = CourseVideo::findOrFail($request->input('id'));
        $video->enabled = $request->input('enabled');
        $video->timestamps = false;
        $video->save();
    }

    public function submitOrderInCourse(Request $request)
    {
        $video = CourseVideo::findOrFail($request->input('id'));
        $video->order_in_course = intval($request->input('order_in_course'));
        $video->timestamps = false;
        $video->save();
    }
}
