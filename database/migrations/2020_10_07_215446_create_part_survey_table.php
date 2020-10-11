<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePartSurveyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('part_survey', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('part_id')->constrained('parts')->onDelete('cascade');
            $table->text('description')->nullable();
            $table->json('data');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('part_survey');
    }
}
