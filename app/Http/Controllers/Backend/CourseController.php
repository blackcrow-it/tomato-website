<?php

namespace App\Http\Controllers\Backend;

use App\Category;
use App\Http\Controllers\Controller;
use App\Http\Requests\CourseRequest;
use App\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function list(Request $request)
    {
        $categories = Category::where('type', Category::TYPE_COURSE)
            ->get()
            ->toTree();

        $list = Course::with('owner')
            ->with('last_editor');

        if ($request->input('category_id')) {
            $categoryIds = Category::descendantsAndSelf($request->input('category_id'))
                ->pluck('id');

            $list = $list->whereIn('category_id', $categoryIds)
                ->orderByRaw('CASE WHEN order_in_category > 0 THEN 0 ELSE 1 END, order_in_category ASC, updated_at DESC');
        } else {
            $list = $list->orderBy('updated_at', 'DESC');
        }

        $list = $list->paginate();

        return view('backend.course.list', [
            'list' => $list,
            'categories' => categories_traverse($categories)
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

        $course->price = $course->price ?? 0;

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

    public function submitEnabled(Request $request)
    {
        $course = Course::findOrFail($request->input('id'));
        $course->enabled = $request->input('enabled');
        $course->timestamps = false;
        $course->save();
    }

    public function getCategoriesTraverse()
    {
        return categories_traverse(
            Category::where('type', Category::TYPE_COURSE)
                ->orderBy('title', 'ASC')
                ->get()
                ->toTree()
        );
    }

    public function submitOrderInCategory(Request $request)
    {
        $course = Course::findOrFail($request->input('id'));
        $course->order_in_category = intval($request->input('order_in_category'));
        $course->timestamps = false;
        $course->save();
    }
}
