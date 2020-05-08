<?php

namespace App\Http\Controllers\Backend;

use App\Category;
use App\Http\Controllers\Controller;
use App\Http\Requests\CourseRequest;
use App\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function list()
    {
        $list = Course::with('owner')
            ->with('last_editor')
            ->orderBy('updated_at', 'DESC')
            ->paginate();

        return view('backend.course.list', [
            'list' => $list
        ]);
    }

    public function add()
    {
        return view('backend.course.edit', [
            'categories' => $this->getCategoriesTraverse()
        ]);
    }

    public function submitAdd(CourseRequest $request)
    {
        $course = new Course;

        $this->processCourseFromRequest($request, $course);

        return redirect()
            ->route('admin.course.edit', ['id' => $course->id])
            ->with('success', 'Thêm khóa học mới thành công.');
    }

    public function edit($id)
    {
        $course = Course::find($id);
        if ($course == null) {
            return redirect()->route('admin.course.list')->withErrors('Khóa học không tồn tại hoặc đã bị xóa.');
        }

        return view('backend.course.edit', [
            'data' => $course,
            'categories' => $this->getCategoriesTraverse()
        ]);
    }

    public function submitEdit(CourseRequest $request, $id)
    {
        $course = Course::find($id);
        if ($course == null) {
            return redirect()->route('admin.course.list')->withErrors('Khóa học không tồn tại hoặc đã bị xóa.');
        }

        $this->processCourseFromRequest($request, $course);

        return redirect()
            ->route('admin.course.edit', ['id' => $course->id])
            ->with('success', 'Thay đổi khóa học thành công.');
    }

    private function processCourseFromRequest(Request $request, Course $course)
    {
        $data = $request->all();
        $course->fill($data);

        $course->save();
    }

    public function submitDelete($id)
    {
        $course = Course::find($id);
        if ($course == null) {
            return redirect()->route('admin.course.list')->withErrors('Khóa học không tồn tại hoặc đã bị xóa.');
        }

        $course->delete();

        return redirect()
            ->route('admin.course.list')
            ->with('success', 'Xóa khóa học thành công.');
    }

    public function submitEnabled(Request $request, $id)
    {
        $course = Course::findOrFail($id);
        $course->enabled = $request->input('enabled');
        $course->save();
    }

    public function getCategoriesTraverse()
    {
        return categories_traverse(
            Category::orderBy('title', 'ASC')
                ->get()
                ->toTree()
        );
    }
}
