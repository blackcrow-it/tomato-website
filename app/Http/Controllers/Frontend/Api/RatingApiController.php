<?php

namespace App\Http\Controllers\Frontend\Api;

use App\Constants\ObjectType;
use App\Course;
use App\Http\Controllers\Controller;
use App\Rating;
use Illuminate\Http\Request;

class RatingApiController extends Controller
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
     * Hàm tạo mới hoặc cập nhật đánh giá
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // if (!$request->input('object_id') || !$request->input('type') || !$request->input('star') || $request->input('star') > 5) {
        //     return response('Missing data', 401);
        // }
        $user_id = auth()->id();
        $object_id = $request->input('object_id');
        $type = $request->input('type');
        switch ($type) {
            case ObjectType::COURSE:
                $item = Course::find($object_id);
                break;
            case ObjectType::COMBO_COURSE:
                $item = Course::find($object_id);
                break;
            case ObjectType::BOOK:
                $item = Course::find($object_id);
                break;
            case ObjectType::POST:
                $item = Course::find($object_id);
                break;
            default:
                return response(['msg' => 'Missing data', 'status' => 'error'], 401);
        }
        if ($item) {
            $rating = Rating::firstOrNew(
                ['user_id' => $user_id],
                ['type' => $type],
                ['object_id' => $object_id],
            );
            $rating->user_id = $user_id;
            $rating->type = $type;
            $rating->object_id = $object_id;
            $rating->star = $request->input('star');
            $rating->comment = $request->input('comment');
            $rating->save();
            return response(['msg' => 'Create or update rating complete', 'status' => 'success'], 200);
        } else {
            return response(['msg' => 'Not found item', 'status' => 'error'], 404);
        }
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
