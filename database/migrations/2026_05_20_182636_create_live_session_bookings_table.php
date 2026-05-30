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
        Schema::create('live_session_bookings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('live_session_id');
            $table->unsignedInteger('student_id');
            $table->unsignedInteger('sale_id')->nullable(); // Links to the financial transaction
            
            $table->string('payment_reference')->nullable()->unique();
            $table->string('refund_reference')->nullable()->unique();
            $table->string('gateway_event_id')->nullable();
            $table->string('gateway_name')->nullable();
            $table->enum('refund_status', ['pending', 'processed', 'failed'])->nullable();
            
            $table->enum('status', ['paid', 'cancelled', 'refunded', 'attended', 'missed'])->default('paid');
            
            $table->timestamps();
            
            $table->foreign('live_session_id')->references('id')->on('live_sessions')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
            
            // CRITICAL: Prevent duplicate bookings per session
            $table->unique(['live_session_id', 'student_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('live_session_bookings');
    }
};
