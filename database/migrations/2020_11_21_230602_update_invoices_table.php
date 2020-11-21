<?php

use App\Constants\InvoiceStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('name', 255)->default('');
            $table->string('phone', 255)->default('');
            $table->boolean('shipping')->default(false);
            $table->string('city', 255)->nullable();
            $table->string('district', 255)->nullable();
            $table->string('address', 255)->nullable();
            $table->string('status', 255)->default(InvoiceStatus::PENDING);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->dropColumn('phone');
            $table->dropColumn('shipping');
            $table->dropColumn('city');
            $table->dropColumn('district');
            $table->dropColumn('address');
            $table->dropColumn('status');
        });
    }
}
