<?php

namespace App\Http\Controllers\Frontend\Api;

use App\Http\Controllers\Controller;
use App\User;
use App\ZoomMeeting;
use Illuminate\Http\Request;

class ZoomApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $meeting_id
     * @return \Illuminate\Http\Response
     */
    public function getSignature(Request $request, $meeting_id)
    {
        $userId = $request->session()->get('user_id', 3533);
        $fullName = 'Học viên Tomato';
        $email = '';
        $meeting = ZoomMeeting::where('meeting_id', $meeting_id)->first();
        if (!$meeting) {
            return response(['msg' => 'Not found'], 404);
        }
        $user = User::find($userId);
        if ($user) {
            $fullName = $user->name;
            $email = $user->email;
        } else {
            return response(['msg' => 'Access Denied'], 403);
        }
        $signature = $this->generate_signature(
            env('ZOOM_API_KEY', ''),
            env('ZOOM_API_SECRET', ''),
            $meeting_id,
            0
        );
        return response([
            'msg' => 'Success',
            'signature' => $signature,
            'name' => $fullName,
            'email' => $email,
            'password' => $meeting->password,
            'leaveUrl' => route('user.my_zoom')
        ]);
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

    public function eventMeeting(Request $request)
    {
        $bodyContent = $request->getContent();
        if ($bodyContent) {
            $meeting = ZoomMeeting::where('meeting_id', $bodyContent['payload']['object']['id'])->first();
            if ($meeting) {
                if ($bodyContent['event'] == 'meeting.started') {
                    $meeting->is_start = true;
                } else if ($bodyContent['event'] == 'meeting.started') {
                    $meeting->is_start = false;
                }
            } else {
                return response([
                    'msg' => 'Not Found'
                ], 404);
            }
        } else {
            return response([
                'msg' => 'Bad request'
            ], 403);
        }
        return response([
            'msg' => 'Success'
        ]);
    }
}
