<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Lesson;
use App\Http\Requests\Backend\PartRequest;
use App\Part;
use DB;
use Exception;
use Illuminate\Http\Request;
use Log;

class PartController extends Controller
{
    public function list(Request $request)
    {
        $lesson = Lesson::find($request->input('lesson_id'));
        if ($lesson == null) {
            return redirect()->route('admin.lesson.list')->withErrors('Bài học không tồn tại hoặc đã bị xóa.');
        }

        $list = Part::where('lesson_id', $lesson->id)
            ->orderByRaw('CASE WHEN order_in_lesson > 0 THEN 0 ELSE 1 END, order_in_lesson ASC')
            ->orderBy('created_at', 'asc')
            ->paginate();

        return view('backend.part.list', [
            'list' => $list,
            'lesson' => $lesson,
            'course' => $lesson->course
        ]);
    }

    public function add(Request $request)
    {
        $lesson = Lesson::find($request->input('lesson_id'));
        if ($lesson == null) {
            return redirect()->route('admin.lesson.list')->withErrors('Bài học không tồn tại hoặc đã bị xóa.');
        }

        return view('backend.part.add', [
            'lesson' => $lesson,
            'course' => $lesson->course
        ]);
    }

    public function submitAdd(PartRequest $request)
    {
        $lesson = Lesson::find($request->input('lesson_id'));
        if ($lesson == null) {
            return redirect()->route('admin.lesson.list')->withErrors('Bài học không tồn tại hoặc đã bị xóa.');
        }

        $part = new Part();

        try {
            DB::beginTransaction();
            $this->processPartFromRequest($request, $part);
            DB::commit();

            return redirect()
                ->route('admin.part_' . $part->type . '.edit', ['part_id' => $part->id, 'lesson_id' => $lesson->id])
                ->with('success', 'Thêm đầu mục mới thành công.');
        } catch (Exception $ex) {
            DB::rollBack();
            Log::error($ex);

            return redirect()
                ->route('admin.part.list', ['lesson_id' => $lesson->id])
                ->withErrors('Thêm đầu mục mới thất bại.');
        }
    }

    private function processPartFromRequest(Request $request, Part $part)
    {
        $data = $request->all();
        $part->fill($data);
        $part->lesson_id = $request->input('lesson_id');
        $part->save();
    }

    public function submitEnabled(Request $request)
    {
        $part = Part::findOrFail($request->input('id'));
        $part->enabled = $request->input('enabled');
        $part->timestamps = false;
        $part->save();
    }

    public function submitEnabledTrial(Request $request)
    {
        $part = Part::findOrFail($request->input('id'));
        $part->enabled_trial = $request->input('enabled_trial');
        $part->timestamps = false;
        $part->save();
    }

    public function submitOrderInLesson(Request $request)
    {
        $part = Part::findOrFail($request->input('id'));
        $part->order_in_lesson = intval($request->input('order_in_lesson'));
        $part->timestamps = false;
        $part->save();
    }
}
