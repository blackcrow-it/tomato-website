<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePracticeTestCategorySessionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('practice_test_category_session', function (Blueprint $table) {
            $table->id();
            $table->string('alias',256);
            $table->foreignId('category_id')->constrained('practice_test_categories')->onDelete('cascade');
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
        Schema::dropIfExists('practice_test_category_session');
    }
}
