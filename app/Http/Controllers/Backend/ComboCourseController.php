<?php

namespace App\Http\Controllers\Backend;

use DB;
use Log;
use Exception;
use App\ComboCourses;
use App\ComboCoursesItem;
use App\Course;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Backend\ComboCourseRequest;

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
        return view('backend.combo_courses.edit', [
        ]);
    }

    public function submitAdd(ComboCourseRequest $request)
    {
        $comboCourse = new ComboCourses;

        try {
            DB::beginTransaction();
            $this->processComboCourseFromRequest($request, $comboCourse);
            DB::commit();

            return redirect()
                ->route('admin.combo_courses.list')
                ->with('success', 'Thêm combo khóa học mới thành công.');
        } catch (Exception $ex) {
            DB::rollBack();
            Log::error($ex);

            return redirect()
                ->route('admin.combo_courses.list')
                ->withErrors('Thêm combo khóa học mới thất bại.');
        }
    }

    public function edit($id)
    {
        $comboCourses = ComboCourses::find($id);
        if ($comboCourses == null) {
            return redirect()->route('admin.course.list')->withErrors('Khóa học không tồn tại hoặc đã bị xóa.');
        }
        $listCourses = array();
        foreach ($comboCourses->items as $comboCoursesItem) {
            $course = Course::find($comboCoursesItem->course_id);
            array_push($listCourses, $course);
        }
        return view('backend.combo_courses.edit', [
            'data' => $comboCourses,
            'courses' => json_encode($listCourses)
        ]);
    }

    public function submitEdit(ComboCourseRequest $request, $id)
    {
        $comboCourse = ComboCourses::find($id);
        if ($comboCourse == null) {
            return redirect()->route('admin.combo_courses.list')->withErrors('Combo khoá học không tồn tại hoặc đã bị xóa.');
        }

        try {
            DB::beginTransaction();
            $this->processComboCourseFromRequest($request, $comboCourse);
            DB::commit();

            return redirect()
                ->route('admin.combo_courses.list')
                ->with('success', 'Thay đổi combo khóa học thành công.');
        } catch (Exception $ex) {
            DB::rollBack();
            Log::error($ex);

            return redirect()
                ->route('admin.combo_courses.list')
                ->withErrors('Thay đổi combo khóa học thất bại.');
        }
    }

    // Hàm xử lý lưu và tạo combo khoá học
    private function processComboCourseFromRequest(Request $request, ComboCourses $comboCourse)
    {
        $data = $request->all();
        $comboCourse->fill($data);
        $comboCourse->save();

        ComboCoursesItem::where('combo_courses_id', $comboCourse->id)->delete();
        $courseIds = $request->input('__courses', []);
        error_log(implode($courseIds));
        foreach ($courseIds as $courseId) {
            $comboCourseItem = new ComboCoursesItem();
            $comboCourseItem->combo_courses_id = $comboCourse->id;
            $comboCourseItem->course_id = $courseId;
            $comboCourseItem->save();
        }
    }

    public function submitDelete($id)
    {
        $comboCourse = ComboCourses::find($id);
        if ($comboCourse == null) {
            return redirect()->route('admin.combo_courses.list')->withErrors('Combo khóa học không tồn tại hoặc đã bị xóa.');
        }

        try {
            DB::beginTransaction();
            $comboCourse->delete();
            DB::commit();

            return redirect()
                ->route('admin.combo_courses.list')
                ->with('success', 'Xóa combo khóa học thành công.');
        } catch (Exception $ex) {
            DB::rollBack();
            Log::error($ex);

            return redirect()
                ->route('admin.combo_courses.list')
                ->withErrors('Xóa combo khóa học thất bại.');
        }
    }

    public function submitEnabled(Request $request)
    {
        $comboCourse = ComboCourses::findOrFail($request->input('id'));
        $comboCourse->enabled = $request->input('enabled');
        $comboCourse->timestamps = false;
        $comboCourse->save();
    }

    public function getCoursesInCombo(Request $request)
    {
        $id = $request->input('id');
        return ComboCoursesItem::with('course')
            ->where('combo_courses_id', $id)
            ->get()
            ->pluck('course');
    }
}
