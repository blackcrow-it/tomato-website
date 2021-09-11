<?php

namespace App\Http\Controllers\Backend;

use App\Category;
use App\ComboCourseRelatedBook;
use DB;
use Log;
use Exception;
use App\ComboCourses;
use App\ComboCoursesItem;
use App\ComboRelatedCombo;
use App\Constants\ObjectType;
use App\Course;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Backend\ComboCourseRequest;
use Route;

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
            'categories' => $this->getCategoriesTraverse(),
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
            'categories' => $this->getCategoriesTraverse(),
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

        ComboRelatedCombo::where('combo_course_id', $comboCourse->id)->delete();
        $relatedComboCourseIds = $request->input('__related_combo_courses', []);
        foreach ($relatedComboCourseIds as $relatedComboCourseId) {
            $related = new ComboRelatedCombo();
            $related->combo_course_id = $comboCourse->id;
            $related->related_combo_course_id = $relatedComboCourseId;
            $related->save();
        }

        ComboCourseRelatedBook::where('combo_course_id', $comboCourse->id)->delete();
        $relatedBookIds = $request->input('__related_books', []);
        foreach ($relatedBookIds as $relatedBookId) {
            $related = new ComboCourseRelatedBook();
            $related->combo_course_id = $comboCourse->id;
            $related->related_book_id = $relatedBookId;
            $related->save();
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

    public function getCategoriesTraverse()
    {
        return categories_traverse(
            Category::where('type', ObjectType::COMBO_COURSE)
                ->orderBy('title', 'ASC')
                ->get()
                ->toTree()
        );
    }

    public function getSearch(Request $request)
    {
        $keyword = $request->input('keyword');
        error_log($keyword);
        if (empty($keyword)) return [];

        $query = ComboCourses::where('enabled', true)
            ->orderBy('title', 'asc');

        if (strpos($keyword, config('app.url')) === 0) {
            $route = Route::getRoutes()->match(Request::create($keyword));
            $query->where('slug', $route->slug);
        } else {
            $query->where('title', 'ilike', "%$keyword%");
        }

        return $query->get();
    }

    public function getRelatedComboCourse(Request $request)
    {
        $id = $request->input('id');
        return ComboRelatedCombo::with('related_combo_course')
            ->where('combo_course_id', $id)
            ->get()
            ->pluck('related_combo_course');
    }

    public function getRelatedBook(Request $request)
    {
        $id = $request->input('id');
        return ComboCourseRelatedBook::with('related_book')
            ->where('combo_course_id', $id)
            ->get()
            ->pluck('related_book');
    }
}
