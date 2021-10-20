<?php

namespace App\Http\Controllers\Backend;

use App\Course;
use App\Http\Controllers\Controller;
use App\Mail\SendMailInviteStudent;
use App\Traits\ZoomMeetingTrait;
use App\User;
use App\UserZoomMeeting;
use App\ZoomMeeting;
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
    public function index($id)
    {
        try {
            $data = $this->getList($id);
            return view('backend.zoom.index', [
                'data' => $data['data'],
                'id' => $id
            ]);
        } catch (\Throwable $th) {
            return redirect()
            ->route('admin.zoom.index_user');
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexUser()
    {
        $data = $this->getListUsers();
        Debugbar::info(json_encode($data));
        return view('backend.zoom.user', [
            'data' => $data
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function new($id)
    {
        return view('backend.zoom.create', [
            'id' => $id
        ]);
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
            ->route('admin.zoom.meetings', ['id' => $request['id']])
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
        $data = ZoomMeeting::where('meeting_id', $id)->first();
        return view('backend.zoom.show', ['meeting' => $data]);
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
            ->route('admin.zoom.show', ['id' => $id])
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
                ->route('admin.zoom.meetings', ['id' => $meeting['owner_id']])
                ->with('success', 'Đã xoá phòng học thành công.');
        } else {
            return redirect()
                ->route('admin.zoom.meetings', ['id' => $meeting['owner_id']])
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
        $users = [];
        $courseIds = $request->input('__invite_courses', []);
        $userIds = $request->input('__invite_users', []);
        $meeting = ZoomMeeting::find($request->input('id_meeting'));
        if ($meeting) {
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
            try {
                UserZoomMeeting::where('meeting_id', $meeting->id)->delete();
            } catch (\Throwable $th) {
                //throw $th;
            }
            foreach ($users as $user) {
                $data = array(
                    'student_name' => $user->name,
                    'email_student' => $user->email,
                    'topic' => $meeting->topic,
                    'id_zoom' => $meeting->meeting_id,
                    'start_time' => $meeting->start_time,
                    'join_url' => $meeting->join_url,
                );
                Mail::to($user->email)->send(new SendMailInviteStudent($data));
                $userMeetingZoom = new UserZoomMeeting();
                $userMeetingZoom->user_id = $user->id;
                $userMeetingZoom->zoom_meeting_id = $meeting->id;
                $userMeetingZoom->save();
            }
            return redirect()
                ->route('admin.zoom.show', ['id' => $meeting->meeting_id])
                ->with('success', 'Đã gửi email đến cho học viên.');
        } else {
            return redirect()
                ->route('admin.zoom.meetings', ['id' => $meeting->owner_id])
                ->withErrors('Phòng học này không tồn tại.');
        }
    }

    public function getUserMeeting($id) {
        return UserZoomMeeting::query()
            ->with('user')
            ->where('zoom_meeting_id', $id)
            ->orderBy('created_at', 'asc')
            ->get();
    }
}
