<?php

use App\Constants\TranscodeStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTranscodeStatusToPartVideoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('part_video', function (Blueprint $table) {
            $table->string('transcode_status', 255)->default(TranscodeStatus::COMPLETED);
            $table->text('transcode_message')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('part_video', function (Blueprint $table) {
            $table->dropColumn('transcode_status');
            $table->dropColumn('transcode_message');
        });
    }
}
