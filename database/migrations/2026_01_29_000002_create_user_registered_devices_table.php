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
        Schema::create('user_registered_devices', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('device_fingerprint', 64)->unique(); // SHA256 hash of device characteristics
            $table->string('device_name', 255)->nullable();
            $table->string('browser', 100)->nullable();
            $table->string('os', 100)->nullable();
            $table->string('platform', 50)->nullable(); // mobile, tablet, desktop
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->integer('first_registered_at')->unsigned();
            $table->integer('last_used_at')->unsigned()->nullable();
            $table->boolean('is_trusted')->default(true);
            $table->integer('login_count')->default(1);
            
            $table->foreign('user_id')->on('users')->references('id')->cascadeOnDelete();
            
            $table->index('user_id');
            $table->index('device_fingerprint');
            $table->index(['user_id', 'device_fingerprint']);
        });

        // Add device_fingerprint to user_active_sessions
        Schema::table('user_active_sessions', function (Blueprint $table) {
            $table->string('device_fingerprint', 64)->nullable()->after('session_id');
            $table->index('device_fingerprint');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_active_sessions', function (Blueprint $table) {
            $table->dropIndex(['device_fingerprint']);
            $table->dropColumn('device_fingerprint');
        });
        
        Schema::dropIfExists('user_registered_devices');
    }
};
