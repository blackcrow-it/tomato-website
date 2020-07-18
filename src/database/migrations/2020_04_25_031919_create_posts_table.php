<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->string('slug', 255);
            $table->string('thumbnail', 255)->nullable();
            $table->string('cover', 255)->nullable();
            $table->text('description')->nullable();
            $table->longText('content')->nullable();
            $table->unsignedInteger('view')->default(0);
            $table->boolean('enabled')->default(true);
            $table->timestamps();

            $table->string('meta_title', 255)->nullable();
            $table->string('meta_description', 255)->nullable();
            $table->string('og_title', 255)->nullable();
            $table->string('og_description', 255)->nullable();
            $table->string('og_image', 255)->nullable();

            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
}
