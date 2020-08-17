<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\PartVideoRequest;
use App\Jobs\UploadStreamfileJob;
use App\Part;
use App\PartVideo;
use DB;
use Exception;
use Illuminate\Http\Request;
use Log;
use Storage;
use Str;

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

        $data = $part->part_video;

        return view('backend.part.edit_video', [
            'video_url' => $data ? Storage::disk('s3')->url($data->s3_path . '/hls/playlist.m3u8') : null,
            'part' => $part,
            'lesson' => $lesson,
            'course' => $course
        ]);
    }

    public function submitEdit(PartVideoRequest $request, $part_id)
    {
        $part = Part::find($part_id);
        if ($part == null) return abort(500);

        $lesson = $part->lesson;
        if ($lesson == null)return abort(500);

        $course = $lesson->course;
        if ($course == null) return abort(500);

        DB::transaction(function () use ($request, $part_id, $course, $lesson, $part) {
            $part->title = $request->input('title');
            $part->save();

            $data = $part->part_video ?? new PartVideo();
            $data->part_id = $part_id;
            $data->s3_path = "part_video/c{$course->id}_l{$lesson->id}_p{$part->id}";
            $data->save();
        });
    }

    public function submitDelete(Request $request, $part_id)
    {
        $part = Part::find($part_id);
        if ($part == null) {
            return redirect()->route('admin.part.list', ['lesson_id' => $request->input('lesson_id')])->withErrors('Đầu mục không tồn tại hoặc đã bị xóa.');
        }

        try {
            Storage::disk('s3')->deleteDirectory($part->part_video->s3_path);

            DB::beginTransaction();
            $part->part_video()->delete();
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

    public function upload(Request $request)
    {
        $part = Part::findOrFail($request->input('part_id'));
        $data = $part->part_video;

        $file = $request->file('file');

        $path = $request->input('path');
        if (str_contains($path, '../')) abort(500);

        $pathInfo = pathinfo($path);

        if ($pathInfo['dirname'] == '.') {
            $file->storeAs(
                $data->s3_path,
                $pathInfo['basename'],
                's3'
            );
        } else {
            $file->storePubliclyAs(
                $data->s3_path . '/' . $pathInfo['dirname'],
                $pathInfo['basename'],
                's3'
            );
        }
    }

    public function clearS3(Request $request)
    {
        $part = Part::findOrFail($request->input('part_id'));
        $data = $part->part_video;

        Storage::disk('s3')->deleteDirectory($data->s3_path);
    }
}
