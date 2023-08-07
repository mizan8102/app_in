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
        Schema::create('purchase_receive_mappings', function (Blueprint $table) {
            $table->id();
            $table->integer('receive_master_id');
            $table->integer('purchase_order_child_id');
            $table->integer('item_information_id');
            $table->double('receive_quantity');
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
        Schema::dropIfExists('purchase_receive_mappings');
    }
};
