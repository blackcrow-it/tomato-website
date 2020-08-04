<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoursePositionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_position', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('code', 255);
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
            $table->unsignedInteger('order_in_position')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course_position');
    }
}
