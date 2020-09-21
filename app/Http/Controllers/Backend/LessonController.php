<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Course;
use App\Http\Requests\Backend\LessonRequest;
use App\Lesson;
use DB;
use Exception;
use Illuminate\Http\Request;
use Log;

class LessonController extends Controller
{
    public function list(Request $request)
    {
        $course = Course::find($request->input('course_id'));
        if ($course == null) {
            return redirect()->route('admin.course.list')->withErrors('Khóa học không tồn tại hoặc đã bị xóa.');
        }

        $list = Lesson::where('course_id', $course->id)
            ->orderByRaw('CASE WHEN order_in_course > 0 THEN 0 ELSE 1 END, order_in_course ASC')
            ->orderBy('created_at', 'asc')
            ->paginate();

        return view('backend.lesson.list', [
            'list' => $list,
            'course' => $course
        ]);
    }

    public function add(Request $request)
    {
        $course = Course::find($request->input('course_id'));
        if ($course == null) {
            return redirect()->route('admin.course.list')->withErrors('Khóa học không tồn tại hoặc đã bị xóa.');
        }

        return view('backend.lesson.edit', [
            'course' => $course
        ]);
    }

    public function submitAdd(LessonRequest $request)
    {
        $course = Course::find($request->input('course_id'));
        if ($course == null) {
            return redirect()->route('admin.course.list')->withErrors('Khóa học không tồn tại hoặc đã bị xóa.');
        }

        $lesson = new Lesson();

        try {
            DB::beginTransaction();
            $this->processLessonFromRequest($request, $lesson);
            DB::commit();

            return redirect()
                ->route('admin.lesson.list', ['course_id' => $course->id])
                ->with('success', 'Thêm bài học mới thành công.');
        } catch (Exception $ex) {
            DB::rollBack();
            Log::error($ex);

            return redirect()
                ->route('admin.lesson.list', ['course_id' => $course->id])
                ->withErrors('Thêm bài học mới thất bại.');
        }
    }

    public function edit(Request $request, $id)
    {
        $lesson = Lesson::find($id);
        if ($lesson == null) {
            return redirect()->route('admin.lesson.list', ['course_id' => $request->input('course_id')])->withErrors('Bài học không tồn tại hoặc đã bị xóa.');
        }

        return view('backend.lesson.edit', [
            'data' => $lesson,
            'course' => $lesson->course
        ]);
    }

    public function submitEdit(LessonRequest $request, $id)
    {
        $lesson = Lesson::find($id);
        if ($lesson == null) {
            return redirect()->route('admin.lesson.list', ['course_id' => $request->input('course_id')])->withErrors('Bài học không tồn tại hoặc đã bị xóa.');
        }

        $course = $lesson->course;

        try {
            DB::beginTransaction();
            $this->processLessonFromRequest($request, $lesson);
            DB::commit();

            return redirect()
                ->route('admin.lesson.list', ['course_id' => $course->id])
                ->with('success', 'Thay đổi bài học thành công.');
        } catch (Exception $ex) {
            DB::rollBack();
            Log::error($ex);

            return redirect()
                ->route('admin.lesson.list', ['course_id' => $course->id])
                ->withErrors('Thay đổi bài học thất bại.');
        }
    }

    private function processLessonFromRequest(Request $request, Lesson $lesson)
    {
        $data = $request->all();
        $lesson->fill($data);
        $lesson->course_id = $request->input('course_id');
        $lesson->save();
    }

    public function submitDelete(Request $request, $id)
    {
        $lesson = Lesson::find($id);
        if ($lesson == null) {
            return redirect()->route('admin.lesson.list', ['course_id' => $request->input('course_id')])->withErrors('Bài học không tồn tại hoặc đã bị xóa.');
        }

        $course = $lesson->course;

        try {
            DB::beginTransaction();
            $lesson->delete();
            DB::commit();

            return redirect()
                ->route('admin.lesson.list', ['course_id' => $course->id])
                ->with('success', 'Xóa bài học thành công.');
        } catch (Exception $ex) {
            DB::rollBack();
            Log::error($ex);

            return redirect()
                ->route('admin.lesson.list', ['course_id' => $course->id])
                ->withErrors('Xóa bài học thất bại.');
        }
    }

    public function submitEnabled(Request $request)
    {
        $lesson = Lesson::findOrFail($request->input('id'));
        $lesson->enabled = $request->input('enabled');
        $lesson->timestamps = false;
        $lesson->save();
    }

    public function submitOrderInCourse(Request $request)
    {
        $lesson = Lesson::findOrFail($request->input('id'));
        $lesson->order_in_course = intval($request->input('order_in_course'));
        $lesson->timestamps = false;
        $lesson->save();
    }
}
