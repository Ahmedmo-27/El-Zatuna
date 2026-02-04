<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('registration_verification_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('token', 128)->unique()->index();
            $table->json('data');
            $table->timestamp('expires_at')->index();
            $table->boolean('used')->default(false)->index();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registration_verification_tokens');
    }
};
