<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePostsTableSupportTempPostForNewAction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->string('title', 255)->nullable()->change();
            $table->string('slug', 255)->nullable()->change();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->string('title', 255)->nullable(false)->change();
            $table->string('slug', 255)->nullable(false)->change();
            $table->dropSoftDeletes();
        });
    }
}
