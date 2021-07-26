<?php

namespace App\Http\Controllers\Frontend;

use App\UserCourse;
use App\Category;
use App\Constants\PartType;
use App\CourseRelatedBook;
use App\Http\Controllers\Controller;
use App\Part;
use App\ProcessPart;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Storage;
use Throwable;
use Auth;

class PartController extends Controller
{
    // Lấy nội dung trong khoá học mà học viên đã thanh toán
    public function index($id)
    {
        $is_open = true;
        $part = Part::where('enabled', true)->find($id);
        if ($part == null) return redirect()->route('home');

        $lesson = $part->lesson()->where('enabled', true)->first();
        if ($lesson == null) return redirect()->route('home');

        $course = $lesson->course()->where('enabled', true)->first();
        if ($course == null) return redirect()->route('home');

        // Kiểm tra học viên có sở hữu khoá học này không
        $isUserOwnedThisCourse = auth()->check()
            ? UserCourse::query()
            ->where('user_id', auth()->id())
            ->where('course_id', $course->id)
            ->where(function ($query) {
                $query->orWhere('expires_on', '>', now());
                $query->orWhereNull('expires_on');
            })
            ->exists()
            : false;

        if (!$isUserOwnedThisCourse && !$part->enabled_trial) return redirect()->route('home');

        $relatedBooks = CourseRelatedBook::query()
            ->with('related_book')
            ->whereHas('related_book', function (Builder $query) {
                $query->where('enabled', true);
            })
            ->where('course_id', $course->id)
            ->orderBy('id', 'asc')
            ->get()
            ->pluck('related_book');

        $lessons = $course->lessons()
            ->where('enabled', true)
            ->orderByRaw('CASE WHEN order_in_course > 0 THEN 0 ELSE 1 END, order_in_course ASC')
            ->orderBy('title', 'asc')
            ->get();

        $lessons->map(function ($lesson) {
            $lesson->parts = $lesson->parts()
                ->where('enabled', true)
                ->orderByRaw('CASE WHEN order_in_lesson > 0 THEN 0 ELSE 1 END, order_in_lesson ASC')
                ->orderBy('created_at', 'asc')
                ->get();
        });

        // Thêm trạng thái bài học đã được mở khi làm qua bài trắc nghiệm
        foreach ($lessons as $lesson) {
            foreach ($lesson->parts as $key_part) {
                $key_part->is_open = $is_open;
                if ($key_part->type == 'test') {
                    $is_open = $key_part->isProcessedWithThisUser();
                }
            }
        }

        $data = $part->{'part_' . $part->type};

        if ($part->type == PartType::VIDEO) {
            try {
                Storage::disk('s3')->setVisibility($data->s3_path . '/hls/playlist.m3u8', 'public');
                Storage::disk('s3')->setVisibility($data->s3_path . '/hls/playlist_720p.m3u8', 'public');
                Storage::disk('s3')->setVisibility($data->s3_path . '/hls/playlist_1080p.m3u8', 'public');
            } catch (Throwable $th) {
                //throw $th;
            }
        }

        return view('frontend.part.' . $part->type, [
            'course' => $course,
            'breadcrumb' => Category::ancestorsAndSelf($course->category_id),
            'lessons' => $lessons,
            'part' => $part,
            'data' => $data,
            'is_owned' => $isUserOwnedThisCourse,
            'stream_url' => $part->type == PartType::VIDEO ? Storage::disk('s3')->url($data->s3_path . '/hls/playlist.m3u8') : null,
            'related_books' => $relatedBooks
        ]);
    }

    public function setCompletePart(Request $request)
    {
        $id = $request->input('part_id');
        $part = Part::findOrFail($id);
        $processPart = ProcessPart::where('part_id', $part->id)
            ->where('user_id', auth()->id())
            ->first();
        if (!$processPart) {
            $processPart = new ProcessPart;
        }
        $processPart->part_id = $part->id;
        $processPart->user_id = auth()->id();
        $processPart->is_check = true;
        $processPart->save();
        return response()->json('success', Response::HTTP_OK);
    }
}
