<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDurationToWebinarChaptersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('webinar_chapters', function (Blueprint $table) {
            if (!Schema::hasColumn('webinar_chapters', 'duration')) {
                $table->integer('duration')->unsigned()->nullable()->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('webinar_chapters', function (Blueprint $table) {
            if (Schema::hasColumn('webinar_chapters', 'duration')) {
                $table->dropColumn('duration');
            }
        });
    }
}

