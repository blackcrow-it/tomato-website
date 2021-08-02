<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSurveysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('surveys', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('part_id')->nullable()->constrained('parts')->onDelete('cascade');
            $table->string('full_name', 255)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('phone_number', 13)->nullable();
            $table->date('birthday')->nullable();
            $table->boolean('is_received')->default(false);
            $table->boolean('is_read')->default(false);
            $table->boolean('is_student')->default(false);
            $table->foreignId('received_by_user_id')->nullable()->constrained('users')->onDelete('cascade');
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
        Schema::dropIfExists('surveys');
    }
}
