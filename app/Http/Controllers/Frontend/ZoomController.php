<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\UserZoomMeeting;
use App\ZoomMeeting;
use Illuminate\Http\Request;

class ZoomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($meeting_id)
    {
        $meeting = ZoomMeeting::where('meeting_id', $meeting_id)->first();
        $fullName = '';
        $email = '';
        $role = 0;
        if (!$meeting) {
            return redirect()->route('user.my_zoom')->withErrors('Không tìm thấy phòng học');
        }
        $isUserOwnedThisZoom = auth()->check()
            ? UserZoomMeeting::query()
            ->where('user_id', auth()->id())
            ->where('zoom_meeting_id', $meeting->id)
            ->exists()
            : false;
        if ($isUserOwnedThisZoom) {
            if ($meeting->is_start) {
                $fullName = auth()->user()->name;
                $email = auth()->user()->email;
                $signature = $this->generate_signature(
                    env('ZOOM_API_KEY', ''),
                    env('ZOOM_API_SECRET', ''),
                    $meeting_id,
                    $role
                );
                return view('frontend.zoom.index', [
                    'meetingId' => $meeting_id,
                    'fullName' => $fullName,
                    'email' => $email,
                    'signature' => $signature,
                    'role' => $role,
                    'password' => $meeting->password,
                ]);
            } else {
                return redirect()->route('user.my_zoom')->withErrors('Lớp học chưa bắt đầu');
            }
        } else {
            return redirect()->route('user.my_zoom')->withErrors('Không có quyền truy cập lớp học');
        }
    }

    function generate_signature ( $api_key, $api_secret, $meeting_number, $role){

        //Set the timezone to UTC
        date_default_timezone_set("UTC");

          $time = time() * 1000 - 30000;//time in milliseconds (or close enough)

          $data = base64_encode($api_key . $meeting_number . $time . $role);

          $hash = hash_hmac('sha256', $data, $api_secret, true);

          $_sig = $api_key . "." . $meeting_number . "." . $time . "." . $role . "." . base64_encode($hash);

          //return signature, url safe base64 encoded
          return rtrim(strtr(base64_encode($_sig), '+/', '-_'), '=');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
