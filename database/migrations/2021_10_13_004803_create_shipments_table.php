<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShipmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('partner')->nullable();
            $table->string('id_partner')->nullable();
            $table->integer('status')->nullable();
            $table->dateTime('pick_date')->nullable();
            $table->dateTime('deliver_date')->nullable();
            $table->integer('ship_money')->nullable();
            $table->boolean('is_fast')->default(true);
            $table->boolean('is_ship_cod')->default(true);
            $table->foreignId('invoice_id')->constrained('invoices')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shipments');
    }
}
