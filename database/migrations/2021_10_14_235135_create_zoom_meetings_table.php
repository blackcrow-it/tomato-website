<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZoomMeetingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zoom_meetings', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->bigInteger('meeting_id')->nullable(false);
            $table->string('owner_id')->nullable(false);
            $table->string('topic')->nullable();
            $table->tinyInteger('type')->nullable();
            $table->dateTime('start_time')->nullable();
            $table->integer('duration')->nullable();
            $table->string('password', 10)->nullable();
            $table->text('agenda')->nullable();
            $table->json('tracking_fields')->nullable();
            $table->json('recurrence')->nullable();
            $table->json('settings')->nullable();
            $table->json('occurrences')->nullable();
            $table->string('join_url')->nullable();
            $table->text('start_url')->nullable();
            $table->boolean('is_start')->default(false);
            $table->foreignId('teacher_id')->nullable()->constrained('teachers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('zoom_meetings');
    }
}
