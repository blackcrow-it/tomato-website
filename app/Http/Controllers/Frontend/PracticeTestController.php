<?php

namespace App\Http\Controllers\Frontend;
use DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\PracticeTest;
class PracticeTestController extends Controller
{

    public function index(Request $request)
    {
        return view('frontend.practice_test.index');
    }

    public function rank(Request $request)
    {
        return view('frontend.practice_test.index', ['ranks'=> []]);
    }

    public function list(Request $request)
    {
        $currentDay = (int)date('w');
        // $practices = DB::table('practice_tests')->select()->with()->where('loop_days', 'like', '%'.$currentDay.'%')
        // ->join('practice_test_shifts','practice_tests.id', '=', 'practice_test_id')->where();
        $practices = PracticeTest::with("shifts", "level")->where('loop_days', 'like', '%'.$currentDay.'%')->get();
        return view('frontend.practice_test.index', ['list'=>  $practices]);
    }

    public function history(Request $request)
    {
        return view('frontend.practice_test.index', ['histories'=> []]);
    }

}
