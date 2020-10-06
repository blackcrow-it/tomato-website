<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\PartVideoRequest;
use App\Part;
use App\PartTest;
use DB;
use Exception;
use Illuminate\Http\Request;
use Log;
use Storage;

class PartTestController extends Controller
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

        $data = $part->part_test;

        return view('backend.part.edit_test', [
            'data' => $data,
            'part' => $part,
            'lesson' => $lesson,
            'course' => $course
        ]);
    }

    public function submitEdit(PartVideoRequest $request, $part_id)
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

        try {
            DB::beginTransaction();

            $part->title = $request->input('title');
            $part->save();

            $data = $part->part_test ?? new PartTest();
            $data->fill($request->input());
            $data->part_id = $part_id;
            $data->s3_path = "part_video/c{$course->id}_l{$lesson->id}_p{$part->id}";
            $data->data = $data->data ?? [];
            $data->correct_requirement = $data->correct_requirement ?? 0;
            $data->save();

            DB::commit();

            return redirect()
                ->route('admin.part.list', ['lesson_id' => $request->input('lesson_id')])
                ->with('success', 'Sửa đầu mục thành công.');
        } catch (Exception $ex) {
            DB::rollBack();
            Log::error($ex);

            return redirect()
                ->route('admin.part.list', ['lesson_id' => $request->input('lesson_id')])
                ->withErrors('Sửa đầu mục thất bại.');
        }
    }

    public function submitDelete(Request $request, $part_id)
    {
        $part = Part::find($part_id);
        if ($part == null) {
            return redirect()->route('admin.part.list', ['lesson_id' => $request->input('lesson_id')])->withErrors('Đầu mục không tồn tại hoặc đã bị xóa.');
        }

        try {
            Storage::disk('s3')->deleteDirectory($part->part_test->s3_path);

            DB::beginTransaction();
            $part->part_test()->delete();
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

    public function uploadAudio(Request $request)
    {
        $part = Part::findOrFail($request->input('part_id'));
        $data = $part->part_test;

        $file = $request->file('audio');
        if ($file == null) return abort(500);

        $path = $file->storePublicly($data->s3_path, 's3');

        return [
            'src' => Storage::disk('s3')->url($path)
        ];
    }

    public function deleteAudio(Request $request) {
        $part = Part::findOrFail($request->input('part_id'));
        $data = $part->part_test;

        $filename = basename($request->input('src'));
        Storage::disk('s3')->delete($data->s3_path . '/' . $filename);
    }
}
