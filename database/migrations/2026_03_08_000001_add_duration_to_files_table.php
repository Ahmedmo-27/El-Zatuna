<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDurationToFilesTable extends Migration
{
    /**
     * Run the migrations.
     * Duration of this section/file in minutes (e.g. video length).
     *
     * @return void
     */
    public function up()
    {
        Schema::table('files', function (Blueprint $table) {
            if (!Schema::hasColumn('files', 'duration')) {
                $table->integer('duration')->unsigned()->nullable()->after('volume');
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
        Schema::table('files', function (Blueprint $table) {
            if (Schema::hasColumn('files', 'duration')) {
                $table->dropColumn('duration');
            }
        });
    }
}
