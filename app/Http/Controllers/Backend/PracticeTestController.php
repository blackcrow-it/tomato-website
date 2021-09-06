<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\PracticeTestRepo;
use App\PracticeTestCategory;
use DB;
use App\PracticeTest;

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
        $data = json_decode($request->all()['data']);
        $pt_props = array(
            'title' => $data->title,
            'duration' => $data->duration,
            'loop_days' => implode(", ", $data->selected_weekdays),
            'loop' => $data->loop,
        'date'=>$data->targetDate);
        $pt = new PracticeTest();
        dd($pt_props);
    }
}
