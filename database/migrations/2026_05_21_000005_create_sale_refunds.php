<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaleRefunds extends Migration
{
    public function up()
    {
        Schema::create('sale_refunds', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('sale_id')->index();
            $table->string('refund_reference')->unique();
            $table->string('gateway_name')->nullable();
            $table->string('gateway_event_id')->nullable();
            $table->enum('status', ['pending','processed','failed'])->default('pending');
            $table->json('payload')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sale_refunds');
    }
}
