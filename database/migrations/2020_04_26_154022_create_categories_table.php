<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->string('slug', 255);
            $table->string('icon', 255)->nullable();
            $table->string('cover', 255)->nullable();
            $table->text('description')->nullable();

            $table->string('meta_title', 255)->nullable();
            $table->string('meta_description', 255)->nullable();
            $table->string('og_title', 255)->nullable();
            $table->string('og_description', 255)->nullable();
            $table->string('og_image', 255)->nullable();

            $table->timestamps();
            $table->nestedSet();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
