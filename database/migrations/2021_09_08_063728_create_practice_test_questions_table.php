<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePracticeTestQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('practice_test_questions', function (Blueprint $table) {
            $table->id();
            $table->text('content')->nullable();
            $table->integer('level')->default(0);
            $table->string('type', 30);
            $table->boolean('enabled')->default(false);
            $table->integer('score')->default(0);
            $table->timestamp('created_at');
            $table->timestamp('updated_at')->nullable();
            $table->foreignId('practice_test_id')->constrained('practice_tests')->onDelete('cascade');
            $table->foreignId('question_session_id')->constrained('practice_test_question_session')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('practice_test_questions');
    }
}
