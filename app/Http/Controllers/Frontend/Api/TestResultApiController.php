<?php

namespace App\Http\Controllers\Frontend\Api;

use App\Http\Controllers\Controller;
use App\TestResult;
use Illuminate\Http\Request;
use Session;

class TestResultApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response('Hello');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $test_id = $request->input('test_id');
        $score = $request->input('score');
        $is_pass = $request->input('is_pass');
        $testResult = new TestResult(
            [
                'test_id' => $test_id,
                'score' => $score,
                'is_pass' => $is_pass
            ]
        );
        $testResult->setCurrentUser();
        $testResult->save();

        $allTestResult = TestResult::where('test_id', $test_id)->where('user_id', auth()->id())->get();
        return response(['data' => $testResult, 'history' => $allTestResult]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\TestResult  $testResult
     * @return \Illuminate\Http\Response
     */
    public function show(TestResult $testResult)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TestResult  $testResult
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TestResult $testResult)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TestResult  $testResult
     * @return \Illuminate\Http\Response
     */
    public function destroy(TestResult $testResult)
    {
        //
    }
}
