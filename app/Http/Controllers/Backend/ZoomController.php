<?php

namespace App\Http\Controllers\Backend;

use App\Course;
use App\Http\Controllers\Controller;
use App\Mail\SendMailInviteStudent;
use App\Traits\ZoomMeetingTrait;
use App\User;
use Debugbar;
use Illuminate\Http\Request;
use Mail;

class ZoomController extends Controller
{
    use ZoomMeetingTrait;

    const MEETING_TYPE_INSTANT = 1;
    const MEETING_TYPE_SCHEDULE = 2;
    const MEETING_TYPE_RECURRING = 3;
    const MEETING_TYPE_FIXED_RECURRING_FIXED = 8;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = $this->getList();
        return view('backend.zoom.index', [
            'data' => $data
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function new()
    {
        return view('backend.zoom.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->create($request->all());

        return redirect()
            ->route('admin.zoom.index')
            ->with('success', 'Đã tạo phòng học thành công.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $meeting = $this->get($id);
        Debugbar::info($meeting);
        return view('backend.zoom.show', compact('meeting'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $this->update($id, $request->all());

        return redirect()
            ->route('admin.zoom.index')
            ->with('success', 'Đã cập nhật phòng học thành công.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $meeting = $this->delete($id);
        if ($meeting['success']) {
            return redirect()
                ->route('admin.zoom.index')
                ->with('success', 'Đã xoá phòng học thành công.');
        } else {
            return redirect()
                ->route('admin.zoom.index')
                ->withErrors('Hệ thống gặp vấn đề chưa xoá được phòng học.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function sendEmailNotify(Request $request)
    {
        // $emails = [];
        $users = [];
        $courseIds = $request->input('__invite_courses', []);
        $userIds = $request->input('__invite_users', []);
        $meeting = $this->get($request->input('id_meeting'));
        foreach ($courseIds as $courseId) {
            $course = Course::find($courseId);
            $listUsers = $course->user_courses;
            foreach ($listUsers as $user) {
                if ($user->user && !in_array($user->user, $users))
                {
                    array_push($users, $user->user);
                }
            }
        }
        foreach ($userIds as $userId) {
            $user = User::find($userId);
            if ($user && !in_array($user, $users))
            {
                array_push($users, $user);
            }
        }
        foreach ($users as $user) {
            if ($meeting['success']) {
                $data = array(
                    'student_name' => $user->name,
                    'email_student' => $user->email,
                    'topic' => $meeting['data']['topic'],
                    'id_zoom' => $meeting['data']['id'],
                    'start_time' => $meeting['data']['start_time'],
                    'join_url' => $meeting['data']['join_url'],
                );
                Mail::to($user->email)->send(new SendMailInviteStudent($data));
            }
        }
        return redirect()
            ->route('admin.zoom.index')
            ->with('success', 'Đã gửi email đến cho học viên.');
    }
}
