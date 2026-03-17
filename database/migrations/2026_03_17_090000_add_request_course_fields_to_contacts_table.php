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
        Schema::table('contacts', function (Blueprint $table) {
            $table->string('contact_type')->default('message')->after('phone');
            $table->string('university_name')->nullable()->after('message');
            $table->string('college_name')->nullable()->after('university_name');
            $table->string('study_field')->nullable()->after('college_name');
            $table->string('course_name')->nullable()->after('study_field');
            $table->unsignedTinyInteger('study_year')->nullable()->after('course_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->dropColumn([
                'contact_type',
                'university_name',
                'college_name',
                'study_field',
                'course_name',
                'study_year',
            ]);
        });
    }
};
