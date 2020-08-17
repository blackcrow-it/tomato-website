<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parts', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('lesson_id')->constrained('lessons')->onDelete('cascade');
            $table->string('title', 255);
            $table->string('type', 255);
            $table->boolean('enabled')->default(true);
            $table->unsignedInteger('order_in_lesson')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('parts');
    }
}
