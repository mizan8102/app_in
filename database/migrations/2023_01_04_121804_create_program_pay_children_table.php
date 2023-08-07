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
        Schema::create('program_pay_children', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_pay_detail_id')->constrained()->oncascade('delete');
            $table->integer('paid');
            $table->integer('due');
            $table->string('pay_method')->nullable();
            $table->string('pay_ref')->nullable();
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
        Schema::dropIfExists('program_pay_children');
    }
};
