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
        Schema::create('live_sessions', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('creator_id')->index(); // Teacher
            $table->unsignedInteger('university_id')->nullable()->index();
            $table->unsignedInteger('faculty_id')->nullable()->index();
            $table->unsignedInteger('course_id')->nullable()->index(); // Optional tie to existing course
            
            $table->string('title');
            $table->text('description')->nullable();
            
            $table->timestamp('start_at')->index();
            $table->timestamp('end_at');
            $table->decimal('price', 15, 2)->unsigned();
            $table->integer('max_students')->unsigned()->nullable();
            $table->integer('confirmed_bookings_count')->unsigned()->default(0);
            
            $table->string('provider')->default('manual_zoom'); // Strategy key
            $table->string('provider_url', 1000)->nullable();
            $table->string('provider_password')->nullable();
            $table->text('instructions')->nullable();
            
            $table->enum('status', ['draft', 'published', 'live', 'completed', 'cancelled', 'archived'])->default('draft')->index();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Constraints
            $table->foreign('creator_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('live_sessions');
    }
};
