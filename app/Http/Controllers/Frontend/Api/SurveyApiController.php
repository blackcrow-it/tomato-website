<?php

namespace App\Http\Controllers\Frontend\Api;

use App\Http\Controllers\Controller;
use App\Part;
use App\Survey;
use App\UserCourse;
use Illuminate\Http\Request;

class SurveyApiController extends Controller
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
        $part_id = $request->input('part_id');
        $course = Part::where('enabled', true)->find($part_id)->lesson()->first()->course()->first();
        $data = $request->input('data');
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

        $survey = new Survey([
            'part_id' => $part_id,
            'full_name' => auth()->user()->name,
            'email' => auth()->user()->email,
            'phone_number' => auth()->user()->phone,
            'birthday' => auth()->user()->birthday,
            'is_student' => $isUserOwnedThisCourse,
            'data' => $data
        ]);
        $survey->save();

        return response(['survey' => $survey]);
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
}
