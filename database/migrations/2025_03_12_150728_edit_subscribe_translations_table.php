<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('subscribe_translations', 'description') && !Schema::hasColumn('subscribe_translations', 'subtitle')) {
            DB::statement("ALTER TABLE subscribe_translations CHANGE description subtitle VARCHAR(255) NULL");
        }

        if (!Schema::hasColumn('subscribe_translations', 'description')) {
            Schema::table('subscribe_translations', function (Blueprint $table) {
                $table->text('description')->nullable();
            });
        }
    }

};
