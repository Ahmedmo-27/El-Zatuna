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
        Schema::create('user_active_sessions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('session_id')->unique(); // Laravel session ID or JWT token ID
            $table->enum('session_type', ['web', 'api'])->default('web');
            $table->string('device_name', 255)->nullable(); // e.g., "iPhone 13 Pro", "Chrome on Windows"
            $table->string('browser', 100)->nullable(); // e.g., "Chrome 120.0"
            $table->string('os', 100)->nullable(); // e.g., "Windows 10", "iOS 17.2"
            $table->string('platform', 50)->nullable(); // e.g., "mobile", "desktop", "tablet"
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->integer('last_activity')->unsigned()->nullable();
            $table->integer('created_at')->unsigned();
            
            $table->foreign('user_id')->on('users')->references('id')->cascadeOnDelete();
            
            $table->index('user_id');
            $table->index('session_type');
            $table->index('last_activity');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_active_sessions');
    }
};
