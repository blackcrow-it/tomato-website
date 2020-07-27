<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_videos', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
            $table->string('title', 255);
            $table->string('thumbnail', 255)->nullable();
            $table->unsignedInteger('order_in_course')->default(0);
            $table->string('stream_url', 255)->nullable();
            $table->string('key_path', 255)->nullable();
            $table->unsignedInteger('percent')->default(0);
            $table->string('status', 255)->default('pending');
            $table->longText('message')->nullable();
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
        Schema::dropIfExists('course_videos');
    }
}
