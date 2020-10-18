<?php

use App\PartTest;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ConvertPartTestToNewFormat extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tests = PartTest::all();
        foreach ($tests as $test) {
            $test->data = array_map(function ($item) {
                $item['type'] = 'multiple-choice';
                return $item;
            }, $test->data);
            $test->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
