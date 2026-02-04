<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUniversityFacultyToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('university_id')->unsigned()->nullable()->after('role_id')->index();
            $table->integer('faculty_id')->unsigned()->nullable()->after('university_id')->index();

            $table->foreign('university_id')
                ->references('id')
                ->on('universities')
                ->nullOnDelete();

            $table->foreign('faculty_id')
                ->references('id')
                ->on('faculties')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['faculty_id']);
            $table->dropForeign(['university_id']);
            $table->dropColumn(['faculty_id', 'university_id']);
        });
    }
}
