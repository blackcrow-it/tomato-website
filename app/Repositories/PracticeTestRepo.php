<?php

namespace App\Repositories;

use App\PracticeTest;
use DB;

class PracticeTestRepo {
    public function getByFilterQuery($filter){
        $query = PracticeTest::query()->with('shifts');
        return $query->orderBy('created_at', 'DESC');
    }
}