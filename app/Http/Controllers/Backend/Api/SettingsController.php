<?php

namespace App\Http\Controllers\Backend\Api;

use App\Http\Controllers\Controller;
use App\Setting;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SettingsController extends Controller
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getInfoBio()
    {
        $data = [
            'bio-title-youtube-1' => null,
            'bio-link-youtube-1' => '#',
            'bio-title-youtube-2' => null,
            'bio-link-youtube-2' => '#',
            'bio-link-fanpage' => '#',
            'bio-gmail' => null,
            'bio-link-zalo' => '#',
            'bio-link-podcast' => '#',
            'bio-link-skype' => '#',
            'bio-link-telegram' => '#',
            'bio-link-linkedin' => '#',
            'bio-hotline' => null,
            'bio-avatar' => null,
        ];
        $info = Setting::where('key', 'like', 'bio-%')->get();
        foreach ($info as $key => $value) {
            if (array_key_exists($value['key'], $data) && $value['value']) {
                $data[$value['key']] = $value['value'];
            }
        }
        return response([
            'data' => $data
        ], 200)->header('Content-Type', 'application/json');
    }
}
