<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCourseVideosTableForStreaming extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('course_videos', function (Blueprint $table) {
            $table->string('m3u8_path', 255)->nullable()->change();
            $table->string('key_path', 255)->nullable();
            $table->integer('job_progress')->default(-1);
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
            $table->json('m3u8_path')->nullable()->change();
            $table->dropColumn('key_path');
            $table->dropColumn('job_progress');
        });
    }
}
