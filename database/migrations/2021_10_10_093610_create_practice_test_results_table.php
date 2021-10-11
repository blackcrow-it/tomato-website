<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePracticeTestResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('practice_test_results', function (Blueprint $table) {
            $table->id();
            $table->integer('score')->default(0);
            $table->integer('number_of_correct')->default(0);
            $table->integer('duration')->default(0);
            $table->integer('max_score')->default(0);
            $table->integer('pass_score')->default(0);
            $table->timestamp('created_at');
            $table->timestamp('updated_at')->nullable();
            $table->foreignId('practice_test_id')->constrained('practice_tests')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('practice_test_results');
    }
}
