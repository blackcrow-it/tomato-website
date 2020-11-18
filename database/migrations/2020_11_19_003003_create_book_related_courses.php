<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookRelatedCourses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('book_related_courses', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('book_id')->constrained('books')->onDelete('cascade');
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
        Schema::dropIfExists('book_related_courses');
    }
}
