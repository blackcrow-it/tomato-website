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
use App\PracticeTestResult;
use App\PracticeTestAnswer;
use Auth;
use Session;
use Exception;
use Log;

class PracticeTestController extends Controller
{

    public function index(Request $request)
    {
        return view('frontend.practice_test.index');
    }

    public function rank(Request $request)
    {
        return view('frontend.practice_test.index', ['ranks' => []]);
    }

    public function list(Request $request)
    {
        $currentDay = (int)date('w');
        // $practices = DB::table('practice_tests')->select()->with()->where('loop_days', 'like', '%'.$currentDay.'%')
        // ->join('practice_test_shifts','practice_tests.id', '=', 'practice_test_id')->where();
        $practices = PracticeTest::with(["shifts"=>function($query){
            $query->orderBy('start_time','asc')->orderBy('end_time', 'asc');
        }], "level")->where('loop_days', 'like', '%' . $currentDay . '%')->get();
        
        return view('frontend.practice_test.index', ['list' =>  $practices]);
    }

    public function history(Request $request)
    {
        return view('frontend.practice_test.index', ['histories' => []]);
    }

    private function _group_by($array, $key)
    {
        $return = array();
        foreach ($array as $val) {
            $return[$val[$key]][] = $val;
        }
        return $return;
    }

    public function test(Request $request, $slug, $id)
    {
        $currentDay = (int)date('w');
        $pt = $this->getPracticeTest($id)->with('level')->first();
        if ($pt == null) {
            return redirect()->route('home');
        }
        $users = PracticeTestResult::where('practice_test_id', $id)->groupBy('user_id',)->select('user_id', DB::raw('count(*) as total'))->pluck('user_id', 'total')->all();
        $language = PracticeTestCategory::where('id', $pt->level->parent_id)->first();
        $sessions = PracticeTestQuestionSession::get()->keyBy('id');
        $questions = PracticeTestQuestion::with(['answers' => function ($query) {
            $query->select('id', 'content', 'index', 'question_id', 'enabled')->orderBy('index', 'asc');
        }])->orderBy('level', 'asc')->where([['practice_test_id', $id], ['enabled', true]])->get();
        $questionGroup = $this->_group_by($questions, 'question_session_id');
        return view('frontend.practice_test.test', ["pt" => $pt, 'questions' => $questionGroup, 'sessions' => $sessions, 'language' => $language, 'users' => $users]);
    }

    public function submitTest(Request $request)
    {

        $ss = Session::get('pt_test');
        if ($ss != null) {
            $pt = $this->getPracticeTest($ss['pt_id'])->first();
            $now = Carbon::now();
            $end = $ss['time']->addMinutes($pt->duration + 1);
            if ($now <= $end) {
                $data = json_decode($request->all()['data']);
                // $questions = PracticeTestQuestion::with(['answers' => function ($query) {
                //     $query->select('id', 'question_id','correct')->orderBy('index', 'asc');
                // }])->orderBy('level', 'asc')->where([['practice_test_id', $pt->id], ['enabled', true]])->get();
                // $questionGroup = $this->_group_by($questions, 'question_session_id');
                //dd($questionGroup);

                $answers = PracticeTestAnswer::with(['question' => function ($query) use ($pt) {
                    $query->select('score', 'id')->where('practice_test_id', $pt->id);
                }])->select('id', 'question_id','correct')->where('correct', true)->get()->all();



                $score = 0;
                $correct = 0;
                foreach ($answers as $answer) {
                    if(in_array($answer->id, array_map(function ($v){
                        return $v->answer;
                    }, $data->answers))){
                        $score+= $answer->question->score;
                        $correct++;
                    }
                }

                Session::remove('pt_test');

                try {
                    DB::beginTransaction();
                    $result = new PracticeTestResult(array('score' => $score,
                    'number_of_correct'=> $correct,
                    'duration'=> $data->duration,
                    'max_score'=> $pt->max_score_override,
                    'pass_score'=> $pt->pass_score_override,
                    'practice_test_id' => $pt->id,
                    'test_date' => Carbon::now(),
                    'user_id'=> Auth::id()));
                    $result->save();
                    DB::commit();
                } catch (Exception $ex) {
                    DB::rollBack();
                    Log::error($ex);
                    return response()->json(['error' => $ex->getMessage()], 500);
                }
                
                return response()->json(['score'=> $score, 'result'=>$answers],200);
            }
        }

        return response()->json([], 500);
    }

    public function startTest(Request $request)
    {
        if ($request->has('pt_id')) {
            $pt_id = $request->get('pt_id');
            $pt = $this->getPracticeTest($pt_id)->first();
            if ($pt != null) {
                $obj = array('user_id' => Auth::id(), 'time' => Carbon::now(), 'pt_id' => $pt_id);
                Session::put('pt_test', $obj);
                return response()->json([], 200);
            }
        }
        return response()->json([], 500);
    }

    private function getPracticeTest($id)
    {
        $currentDay = (int)date('w');
        $pt = PracticeTest::where([['id', $id], ['enabled', true]])->where(function ($query) use ($currentDay) {
            $query->where([['loop', true], ['loop_days', 'like', '%' . $currentDay . '%']])
                ->orWhere('date', '=', Carbon::today()->toDateString());
        });
        return $pt;
    }
}