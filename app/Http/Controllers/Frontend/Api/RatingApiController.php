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
    public function index(Request $request)
    {
        $avgStar = 0;
        $totalStar = 0;
        $rank = [
            'starOne' => 0,
            'starTwo' => 0,
            'starThree' => 0,
            'starFour' => 0,
            'starFive' => 0
        ];
        $object_id = $request->input('object_id');
        $type = $request->input('type');
        $ratings = Rating::query()->with(array('user' => function($query) {
            $query->select('id', 'name', 'avatar');
        }))->where('type', $type)
        ->where('object_id', $object_id)
        ->where('visible', true)
        ->orderBy('star', 'DESC')
        ->orderBy('updated_at', 'DESC')
        ->paginate(5);
        $ratingsOverview = Rating::query()->where('type', $type)->where('object_id', $object_id)->get();
        foreach ($ratingsOverview as $rate) {
            $totalStar += $rate->star;
            switch ($rate->star) {
                case 1:
                    $rank['starOne'] += 1;
                    break;
                case 2:
                    $rank['starTwo'] += 1;
                    break;
                case 3:
                    $rank['starThree'] += 1;
                    break;
                case 4:
                    $rank['starFour'] += 1;
                    break;
                case 5:
                    $rank['starFive'] += 1;
                    break;
                default:
                    break;
            }
        }
        if (count($ratingsOverview) > 0) {
            $avgStar = $totalStar / count($ratingsOverview);
        } else {
            $avgStar = 0;
        }
        return response([
            'data' => $ratings,
            'avgStar' => $avgStar,
            'rank' => $rank,
        ]);
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
            $rating = Rating::where('user_id', $user_id)->where('type', $type)->where('object_id', $object_id)->first();
            if (!$rating) {
                $rating = new Rating();
            }
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
