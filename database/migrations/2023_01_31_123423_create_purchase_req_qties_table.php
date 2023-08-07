<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_req_qties', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('purchase_order_child_id');
            $table->bigInteger('purchase_req_master_id');
            $table->bigInteger('item_information_id');
            $table->bigInteger('order_quantity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_req_qties');
    }
};
