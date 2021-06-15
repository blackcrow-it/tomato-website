<?php

namespace App\Http\Controllers\Backend;

use App\ComboCourses;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ComboCourseController extends Controller
{
    public function list(Request $request)
    {
        $list = ComboCourses::orderBy('created_at', 'desc')->paginate();

        return view('backend.combo_courses.list', [
            'list' => $list
        ]);
    }

    public function add()
    {
        return view('backend.course.edit', [
            'categories' => $this->getCategoriesTraverse(),
            'teachers' => Teacher::orderBy('name', 'asc')->get()
        ]);
    }

    public function submitAdd(CourseRequest $request)
    {
        $course = new Course;

        try {
            DB::beginTransaction();
            $this->processCourseFromRequest($request, $course);
            DB::commit();

            return redirect()
                ->route('admin.course.list')
                ->with('success', 'Thêm khóa học mới thành công.');
        } catch (Exception $ex) {
            DB::rollBack();
            Log::error($ex);

            return redirect()
                ->route('admin.course.list')
                ->withErrors('Thêm khóa học mới thất bại.');
        }
    }

    public function edit($id)
    {
        $course = Course::find($id);
        if ($course == null) {
            return redirect()->route('admin.course.list')->withErrors('Khóa học không tồn tại hoặc đã bị xóa.');
        }

        $course->__template_position = $course->position->pluck('code')->toArray();

        return view('backend.course.edit', [
            'data' => $course,
            'categories' => $this->getCategoriesTraverse(),
            'teachers' => Teacher::orderBy('name', 'asc')->get()
        ]);
    }

    public function submitEdit(CourseRequest $request, $id)
    {
        $course = Course::find($id);
        if ($course == null) {
            return redirect()->route('admin.course.list')->withErrors('Khóa học không tồn tại hoặc đã bị xóa.');
        }

        try {
            DB::beginTransaction();
            $this->processCourseFromRequest($request, $course);
            DB::commit();

            return redirect()
                ->route('admin.course.list')
                ->with('success', 'Thay đổi khóa học thành công.');
        } catch (Exception $ex) {
            DB::rollBack();
            Log::error($ex);

            return redirect()
                ->route('admin.course.list')
                ->withErrors('Thay đổi khóa học thất bại.');
        }
    }

    private function processCourseFromRequest(Request $request, Course $course)
    {
        $data = $request->all();
        $course->fill($data);
        $course->intro_youtube_id = get_youtube_id_from_url($course->intro_youtube_id);
        $course->save();

        $positionData = CoursePosition::where('course_id', $course->id)->get();
        CoursePosition::where('course_id', $course->id)->delete();
        $templatePositionCodeArray = $request->input('__template_position', []);
        foreach ($templatePositionCodeArray as $code) {
            $position = new CoursePosition();
            $position->code = $code;
            $position->course_id = $course->id;
            $position->order_in_position = $positionData->firstWhere('code', $code)->order_in_position ?? 0;
            $position->save();
        }

        CourseRelatedCourse::where('course_id', $course->id)->delete();
        $relatedCourseIds = $request->input('__related_courses', []);
        foreach ($relatedCourseIds as $relatedCourseId) {
            $related = new CourseRelatedCourse();
            $related->course_id = $course->id;
            $related->related_course_id = $relatedCourseId;
            $related->save();
        }

        CourseRelatedBook::where('course_id', $course->id)->delete();
        $relatedBookIds = $request->input('__related_books', []);
        foreach ($relatedBookIds as $relatedBookId) {
            $related = new CourseRelatedBook();
            $related->course_id = $course->id;
            $related->related_book_id = $relatedBookId;
            $related->save();
        }
    }

    public function submitDelete($id)
    {
        $course = Course::find($id);
        if ($course == null) {
            return redirect()->route('admin.course.list')->withErrors('Khóa học không tồn tại hoặc đã bị xóa.');
        }

        if ($course->lessons()->count() > 0) {
            return redirect()->route('admin.course.list')->withErrors('Không thể xóa khóa học vì vẫn còn bài học bên trong.');
        }

        try {
            DB::beginTransaction();
            $course->delete();
            DB::commit();

            return redirect()
                ->route('admin.course.list')
                ->with('success', 'Xóa khóa học thành công.');
        } catch (Exception $ex) {
            DB::rollBack();
            Log::error($ex);

            return redirect()
                ->route('admin.course.list')
                ->withErrors('Xóa khóa học thất bại.');
        }
    }

    public function submitEnabled(Request $request)
    {
        $course = Course::findOrFail($request->input('id'));
        $course->enabled = $request->input('enabled');
        $course->timestamps = false;
        $course->save();
    }
}
