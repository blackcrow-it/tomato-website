<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePracticeTestSessionResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('practice_test_session_results', function (Blueprint $table) {
            $table->id();
            $table->integer('score')->default(0);
            $table->integer('max_score')->default(0);
            $table->integer('pass_score')->default(0);
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('created_at');
            $table->foreignId('practice_test_results_id')->constrained('practice_test_results')->onDelete('cascade');
            $table->foreignId('practice_test_session_id')->nullable()->constrained('practice_test_question_session')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('practice_test_session_results');
    }
}
