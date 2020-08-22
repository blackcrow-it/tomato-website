<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\PartVideoRequest;
use App\Part;
use App\PartYoutube;
use DB;
use Exception;
use Illuminate\Http\Request;
use Log;

class PartYoutubeController extends Controller
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

        $data = $part->part_youtube;

        return view('backend.part.edit_youtube', [
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
            preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $request->input('youtube_id'), $match);
            $youtubeId = get_youtube_id_from_url($request->input('youtube_id'));

            DB::beginTransaction();

            $part->title = $request->input('title');
            $part->save();

            $data = $part->part_youtube ?? new PartYoutube();
            $data->part_id = $part_id;
            $data->youtube_id = $youtubeId;
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
            DB::beginTransaction();
            $part->part_youtube()->delete();
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
