<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestExamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_exams', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->json('data')->nullable();
            $table->integer('time')->default(0)->nullable();
            $table->date('date_exam')->nullable();
            $table->boolean('status')->default(true)->nullable();
            $table->integer('score')->default(0)->nullable();
            $table->date('started_at')->nullable();
            $table->date('ended_at')->nullable();
            $table->json('related_course')->nullable();
            $table->foreignId('category_id')->nullable()->constrained('category_exams')->onDelete('cascade');
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
        Schema::dropIfExists('test_exams');
    }
}
