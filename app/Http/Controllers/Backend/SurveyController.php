<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Repositories\SurveyRepo;
use App\Survey;
use Exception;
use Illuminate\Http\Request;
use Log;

class SurveyController extends Controller
{
    private $surveyRepo;

    public function __construct(SurveyRepo $surveyRepo)
    {
        $this->surveyRepo = $surveyRepo;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request)
    {
        $list = Survey::orderBy('created_at', 'DESC')->paginate();
        return view('backend.survey.list', [
            'list' => $list
        ]);
    }

    public function detail($id)
    {
        $survey = Survey::find($id);
        $survey->is_read = true;
        $survey->save();
        return view('backend.survey.detail', [
            'survey' => $survey
        ]);
    }

    public function received($id)
    {
        try {
            $survey = Survey::find($id);
            $survey->is_received = true;
            $survey->received_by_user_id = auth()->user()->id;
            $survey->save();
            return redirect()
                    ->route('admin.survey.list')
                    ->with('success', 'Đã duyệt Phiếu khảo sát #'.$survey->id);
        } catch (Exception $ex) {
            Log::error($ex);

            return redirect()
                ->route('admin.survey.list')
                ->withErrors('Duyệt Phiếu khảo sát #'.$survey->id.' thất bại.');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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
     * @param  \App\Survey  $survey
     * @return \Illuminate\Http\Response
     */
    public function show(Survey $survey)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Survey  $survey
     * @return \Illuminate\Http\Response
     */
    public function edit(Survey $survey)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Survey  $survey
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Survey $survey)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Survey  $survey
     * @return \Illuminate\Http\Response
     */
    public function destroy(Survey $survey)
    {
        //
    }
}
