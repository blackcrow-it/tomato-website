<?php

use Doctrine\DBAL\Schema\Table;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostPositionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_position', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('code', 255);
            $table->foreignId('post_id')->constrained('posts')->onDelete('cascade');
            $table->unsignedInteger('order_in_position')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('post_position');
    }
}
