<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\PracticeTestRepo;
use App\PracticeTestCategory;
use App\PracticeTestQuestion;
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
                'duration' => $data->duration ?: null,
                'loop_days' => implode(", ", $data->selected_weekdays) ?: null,
                'loop' => $data->loop ?: false,
                'max_score_override' => $data->max_score_override ?: 0,
                'pass_score_override' => $data->pass_score_override ?: 0,
                'date' => $data->targetDate,
                'enabled' => $data->enabled ?: false,
                'created_at' => Carbon::now(),
                'category_id' => $data->language_id,
                'date' => $data->targetDate ?: false
            );
            //$pt->fill($pt_props);
            //$pt->save();
        }

        $input_questions = $data->questions;
        $db_questions = PracticeTestQuestion::where('practice_test_id', $id);
        $db_questions->whereNotIn('id', array_map(function ($value) {
            return $value->id;
        }, $input_questions))->delete();

        foreach ($input_questions as $question) {
            $exist = $db_questions->where('id', $question->id)->first();
            if(is_null($exist)){
                $exist = new PracticeTestQuestion(array('content'=> $question->content));
            }
        }
    }
}
