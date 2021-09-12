<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComboCourseRelatedComboCourseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('combo_course_related_combo_course', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('combo_course_id')->constrained('combo_courses')->onDelete('cascade');
            $table->foreignId('related_combo_course_id')->constrained('combo_courses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('combo_course_related_combo_course');
    }
}
