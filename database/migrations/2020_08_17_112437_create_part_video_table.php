<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePartVideoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('part_video', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('part_id')->constrained('parts')->onDelete('cascade');
            $table->string('stream_path', 255)->nullable();
            $table->string('key_path', 255)->nullable();
            $table->string('upload_status', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('part_video');
    }
}
