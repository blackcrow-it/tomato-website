<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResultExamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('result_exams', function (Blueprint $table) {
            $table->id();
            $table->integer('time')->default(0)->nullable();
            $table->integer('score')->default(0)->nullable();
            $table->date('date_exam')->nullable();
            $table->boolean('is_pass')->default(false)->nullable();
            $table->foreignId('test_exam_id')->nullable()->constrained('test_exams')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('result_exams');
    }
}
