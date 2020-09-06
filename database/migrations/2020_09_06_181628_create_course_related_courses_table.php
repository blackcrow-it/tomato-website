<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseRelatedCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_related_courses', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
            $table->foreignId('related_course_id')->constrained('courses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course_related_courses');
    }
}
