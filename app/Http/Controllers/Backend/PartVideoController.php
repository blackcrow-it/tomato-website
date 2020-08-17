<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\PartRequest;
use App\Part;
use App\PartVideo;
use DB;
use Exception;
use Illuminate\Http\Request;
use Log;

class PartVideoController extends Controller
{
    public function edit(Request $request, $part_id)
    {
        $part = Part::find($part_id);
        if ($part == null) {
            return redirect()->route('admin.part.list', ['lesson_id' => $request->input('lesson_id')])->withErrors('Đầu mục không tồn tại hoặc đã bị xóa.');
        }

        $lesson = $part->lesson;
        if ($lesson == null) {
            return redirect()->route('admin.lesson.list')->withErrors('Đầu mục không tồn tại hoặc đã bị xóa.');
        }

        $course = $lesson->course;
        if ($course == null) {
            return redirect()->route('admin.course.list')->withErrors('Khóa học không tồn tại hoặc đã bị xóa.');
        }

        $data = $part->part_videos;

        return view('backend.part.edit_video', [
            'data' => $data,
            'part' => $part,
            'lesson' => $lesson,
            'course' => $course
        ]);
    }

    public function submitEdit(PartRequest $request, $part_id)
    {
        $part = Part::find($part_id);
        if ($part == null) {
            return redirect()->route('admin.part.list', ['lesson_id' => $request->input('lesson_id')])->withErrors('Đầu mục không tồn tại hoặc đã bị xóa.');
        }

        $lesson = $part->lesson;
        if ($lesson == null) {
            return redirect()->route('admin.lesson.list')->withErrors('Đầu mục không tồn tại hoặc đã bị xóa.');
        }

        $course = $lesson->course;
        if ($course == null) {
            return redirect()->route('admin.course.list')->withErrors('Khóa học không tồn tại hoặc đã bị xóa.');
        }

        $data = $part->part_videos ?? new PartVideo();

        try {
            DB::beginTransaction();
            $this->processDataFromRequest($request, $data);
            DB::commit();

            return redirect()
                ->route('admin.part.list', ['lesson_id' => $request->input('lesson_id')])
                ->with('success', 'Thay đổi đầu mục thành công.');
        } catch (Exception $ex) {
            DB::rollBack();
            Log::error($ex);

            return redirect()
                ->route('admin.part.list', ['lesson_id' => $request->input('lesson_id')])
                ->withErrors('Thay đổi đầu mục thất bại.');
        }
    }

    private function processDataFromRequest(Request $request, PartVideo $data)
    {
        $input = $request->all();
        $data->fill($input);
        $data->part_id = $request->input('part_id');
        $data->save();
    }

    public function submitDelete(Request $request, $part_id)
    {
        $part = Part::find($part_id);
        if ($part == null) {
            return redirect()->route('admin.part.list', ['lesson_id' => $request->input('lesson_id')])->withErrors('Đầu mục không tồn tại hoặc đã bị xóa.');
        }

        try {
            DB::beginTransaction();
            $part->part_videos()->delete();
            $part->delete();
            DB::commit();

            return redirect()
                ->route('admin.part.list', ['lesson_id' => $request->input('lesson_id')])
                ->with('success', 'Xóa đầu mục thành công.');
        } catch (Exception $ex) {
            DB::rollBack();
            Log::error($ex);

            return redirect()
                ->route('admin.part.list', ['lesson_id' => $request->input('lesson_id')])
                ->withErrors('Xóa đầu mục thất bại.');
        }
    }
}
