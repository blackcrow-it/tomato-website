<?php

namespace App\Http\Controllers\Backend;

use App\Constants\PartType;
use App\Http\Controllers\Controller;
use App\Part;
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

    public function listSurvey(Request $request)
    {
        $list = Part::where('type', PartType::SURVEY)->orderBy('created_at', 'DESC')->paginate();
        return view('backend.survey.list-survey', [
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

    public function statistic($partId)
    {
        $partSurvey = Part::find($partId);
        $surveys = Survey::where('part_id', $partId)->get();
        $questions = $partSurvey->part_survey->data;
        $index = 0;
        if ($questions == null) {
            $questions = array();
        }
        foreach ($questions as $question) {
            if ($question['type'] == 'radio') {
                $questions[$index]['answers'] = array_fill(0, count($question['options']), 0);
                $questions[$index]['comments'] = array();
            } elseif ($question['type'] == 'textarea') {
                $questions[$index]['answers'] = array();
            }
            $questions[$index]['totalAnswer'] = 0;
            $index++;
        }
        foreach ($surveys as $survey) {
            error_log($survey->id);
            $index = 0;
            foreach ($survey->data as $answer) {
                if ($answer['type'] == 'radio') {
                    if (array_key_exists('answer', $answer)) {
                        $questions[$index]['answers'][$answer['answer']] += 1;
                        $questions[$index]['totalAnswer'] += 1;
                    }
                    if (array_key_exists('comment', $answer) && $answer['comment'] != null) {
                        array_push($questions[$index]['comments'], $answer['comment']);
                    }
                } elseif ($answer['type'] == 'textarea') {
                    if (array_key_exists('answer', $answer) && $answer['answer'] != null) {
                        array_push($questions[$index]['answers'], $answer['answer']);
                        $questions[$index]['totalAnswer'] += 1;
                    }
                }
                $index++;
            }
        }
        return view('backend.survey.statistic', [
            'part_survey' => $partSurvey,
            'surveys' => $surveys,
            'questions' => $questions
        ]);
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
