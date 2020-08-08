<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostRelatedPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_related_posts', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('post_id')->constrained('posts')->onDelete('cascade');
            $table->foreignId('related_post_id')->constrained('posts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('post_related_posts');
    }
}
