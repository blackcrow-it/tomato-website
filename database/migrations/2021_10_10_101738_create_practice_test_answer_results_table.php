<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePracticeTestAnswerResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('practice_test_answer_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('practice_test_question_id')->constrained('practice_test_questions')->onDelete('cascade');
            $table->foreignId('practice_test_results_id')->constrained('practice_test_results')->onDelete('cascade');
            $table->foreignId('practice_test_answers_id')->constrained('practice_test_answers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('practice_test_answer_results');
    }
}
