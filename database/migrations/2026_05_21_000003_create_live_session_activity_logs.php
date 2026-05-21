<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLiveSessionActivityLogs extends Migration
{
    public function up()
    {
        Schema::create('live_session_activity_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('live_session_id')->index();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->unsignedBigInteger('booking_id')->nullable()->index();
            $table->string('action');
            $table->text('description')->nullable();
            $table->json('payload')->nullable();
            $table->timestamps();

            $table->foreign('live_session_id')->references('id')->on('live_sessions')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('live_session_activity_logs');
    }
}
