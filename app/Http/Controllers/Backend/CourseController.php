<?php

namespace App\Http\Controllers\Backend;

use App\Category;
use App\Constants\ObjectType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\CourseRequest;
use App\Course;
use App\CoursePosition;
use App\Repositories\CourseRepo;
use DB;
use Exception;
use Illuminate\Http\Request;
use Log;
use Route;

class CourseController extends Controller
{
    private $courseRepo;

    public function __construct(CourseRepo $courseRepo)
    {
        $this->courseRepo = $courseRepo;
    }

    public function list(Request $request)
    {
        $list = $this->courseRepo->getByFilterQuery($request->input('filter'))->paginate();

        return view('backend.course.list', [
            'list' => $list,
            'categories' => $this->getCategoriesTraverse()
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
        $course = Course::with('videos')->find($id);
        if ($course == null) {
            return redirect()->route('admin.course.list')->withErrors('Khóa học không tồn tại hoặc đã bị xóa.');
        }

        $course->__template_position = $course->position->pluck('code')->toArray();

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
    }

    public function submitDelete($id)
    {
        $course = Course::find($id);
        if ($course == null) {
            return redirect()->route('admin.course.list')->withErrors('Khóa học không tồn tại hoặc đã bị xóa.');
        }

        if ($course->lesson()->count() > 0) {
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

    public function getCategoriesTraverse()
    {
        return categories_traverse(
            Category::where('type', ObjectType::COURSE)
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

    public function submitOrderInPosition(Request $request)
    {
        $position = CoursePosition::where([
            'course_id' => $request->input('id'),
            'code'      => $request->input('code')
        ])->firstOrFail();
        $position->order_in_position = intval($request->input('order_in_position'));
        $position->timestamps = false;
        $position->save();
    }

    public function getSearchCourse(Request $request)
    {
        $keyword = $request->input('keyword');
        if (empty($keyword)) return [];

        $query = Course::where('enabled', true)
            ->orderBy('updated_at', 'desc');

        if (strpos($keyword, config('app.url')) === 0) {
            $route = Route::getRoutes()->match(Request::create($keyword));
            $query->where('slug', $route->slug);
        } else {
            $query->where('title', 'ilike', "%$keyword%");
        }

        return $query->get();
    }
}
