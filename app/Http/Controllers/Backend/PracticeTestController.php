<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\PracticeTestRepo;
use App\PracticeTestCategory;
use App\PracticeTestQuestion;
use App\PracticeTestAnswer;
use App\PracticeTestShift;
use DB;
use App\PracticeTest;
use Carbon\Carbon;
use Exception;
use Log;

class PracticeTestController extends Controller
{
    private $practiceTestRepo;

    public function __construct(PracticeTestRepo $practiceTestRepo)
    {
        $this->practiceTestRepo = $practiceTestRepo;
    }

    public function list(Request $request)
    {
        $list = $this->practiceTestRepo->getByFilterQuery($request->input('filter'))->paginate();
        return view('backend.practice_test.list', ['list' => $list]);
    }

    public function add()
    {
        return view('backend.practice_test.edit', [
            'languages' => PracticeTestCategory::where('type', "language")
                ->orderBy('title', 'ASC')
                ->get()
        ]);
    }

    public function get_categories(Request $request)
    {
        $type = $request->input('type');
        $parent_id = $request->input('parent_id');
        $query = PracticeTestCategory::where('type', $type);
        if ($parent_id != null) {
            $query->where('parent_id', $parent_id);
        }

        return response()->json([
            'data' => $query->orderBy('title', 'ASC')->get()
        ]);
    }

    public function submitAdd(Request $request)
    {
        try {
            DB::beginTransaction();
            $this->processFromRequest($request);
            DB::commit();
        } catch (Exception $ex) {
            DB::rollBack();
            Log::error($ex);
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }

    private function processFromRequest($request)
    {
        $data = json_decode($request->all()['data']);
        $id = $data->id;
        $questions = [];
        $pt = new PracticeTest();
        if (is_null($id)) {
            $pt_props = array(
                'title' => $data->title ?: null,
                'duration' => $data->duration ?: 0,
                'loop_days' => implode(",", $data->selected_weekdays) ?: null,
                'loop' => $data->loop ?: false,
                'max_score_override' => $data->max_score_override ?: 0,
                'pass_score_override' => $data->pass_score_override ?: 0,
                'date' => !is_null($data->targetDate)??Carbon::parse($data->targetDate),
                'enabled' => $data->enabled ?: false,
                'created_at' => Carbon::now(),
                'category_id' => $data->language_id,
                'date' => Carbon::parse($data->targetDate) ?: null
            );
            $pt->fill($pt_props);
            $pt->save();
        }

        $input_questions = $data->questions;
        $db_questions = PracticeTestQuestion::where('practice_test_id', $id);

        //xoá câu hỏi
        $db_questions->whereNotIn('id', array_map(function ($value) {
            return $value->id;
        }, $data->shifts))->delete();


        $db_shifts =  PracticeTestShift::where('practice_test_id', $id);
        //xoá ca thi
        $db_shifts->whereNotIn('id', array_map(function ($value) {
            return $value->id;
        }, $input_questions))->delete();

        foreach ($data->shifts as $shift) {
            $exist = $db_shifts->where('id', $shift->id)->first();
            if(is_null($exist)){
                $exist = new PracticeTestShift(array(
                    'practice_test_id'=> $pt->id,
                    'start_time'=> Carbon::parse($shift->start_time),
                    'end_time'=> Carbon::parse($shift->end_time),
                    'created_at' => Carbon::now()));
            }
            $exist->save();
        }

        foreach ($input_questions as $question) {
            $exist = $db_questions->where('id', $question->id)->first();
            
            if(is_null($exist)){
                $exist = new PracticeTestQuestion(array(
                    'content'=> $question->value,
                    'level'=> $question->order,
                    'type'=> $question->type,
                    'enabled'=> $question->enabled,
                    'created_at' => Carbon::now(),
                    'practice_test_id'=> $pt->id,
                    'question_session_id'=> $question->session_id));
                $exist->save();
                
                foreach ($question->answers as $answer) {
                    $new_answer = new PracticeTestAnswer(array(
                        'question_id' =>$exist->id,
                        'correct'=> $answer->correct,
                        'index'=> $answer->order,
                        'enabled'=> $answer->enabled,
                        'content'=> $answer->value,
                        'created_at' => Carbon::now()));    
                    $new_answer->save();
                }
            }
        }
    }
}
