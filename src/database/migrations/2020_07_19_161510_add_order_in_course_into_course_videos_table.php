<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrderInCourseIntoCourseVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('course_videos', function (Blueprint $table) {
            $table->unsignedInteger('order_in_course')->default(0);
            $table->boolean('enabled')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('course_videos', function (Blueprint $table) {
            $table->dropColumn('order_in_course');
            $table->dropColumn('enabled');
        });
    }
}
