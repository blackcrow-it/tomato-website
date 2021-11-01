<?php

namespace App\Repositories;

use App\PracticeTest;
use App\PracticeTestResult;
use DB;

class PracticeTestRepo {
    public function getByFilterQuery($filter){
        $query = PracticeTest::query()->with('shifts');
        return $query->orderBy('created_at', 'DESC');
    }

    public function getResultsByPracticeTestId($id){
        return PracticeTestResult::where('practice_test_id', $id)->orderBy('score', 'desc');
    }

    public function getResultById($id){
        return PracticeTestResult::where('id', $id)->first();
    }

    public function getPracticeTestById($id){
        return PracticeTest::where('id', $id)->first();
    }

    public function getResultPosition($id, $month, $year){
        return DB::select('select * from (select id, row_number() OVER (ORDER BY score desc) from practice_test_results where extract(month from "test_date") = :month and extract(year from "test_date") = :year order by "score" asc) as temp where id = :id', ['id'=>$id, 'month'=> $month, 'year'=>$year]);
    }
}