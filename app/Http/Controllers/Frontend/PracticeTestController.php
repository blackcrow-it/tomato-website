<?php

namespace App\Http\Controllers\Frontend;
use DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\PracticeTest;
use App\PracticeTestQuestion;
use Carbon\Carbon;
use App\PracticeTestQuestionSession;
use App\PracticeTestCategory;
use Auth;
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

    private function _group_by($array, $key) {
        $return = array();
        foreach($array as $val) {
            $return[$val[$key]][] = $val;
        }
        return $return;
    }

    public function test(Request $request, $slug, $id)
    {
        $currentDay = (int)date('w');
        $pt = PracticeTest::with('level')->where([['id', $id], ['enabled', true]])->where(function($query) use ($currentDay){
            $query->where([['loop', true], ['loop_days', 'like', '%'.$currentDay.'%']])
            ->orWhere('date', '=', Carbon::today()->toDateString());
        })->first();
        if($pt == null) {
            return redirect()->route('home');
        }
        $language = PracticeTestCategory::where('id', $pt->level->parent_id)->first();
        $sessions = PracticeTestQuestionSession::get()->keyBy('id');
        $questions = PracticeTestQuestion::with('answers')->where([['practice_test_id', $id], ['enabled', true]])->get();
        $questionGroup = $this->_group_by($questions, 'question_session_id');
        return view('frontend.practice_test.test', ["pt"=>$pt, 'questions'=>$questionGroup, 'sessions'=> $sessions, 'language'=> $language]);
    }

   

}
