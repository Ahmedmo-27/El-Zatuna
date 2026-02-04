<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use \Illuminate\Support\Facades\DB;

class EditDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('discounts', function (Blueprint $table) {
            if (Schema::hasColumn('discounts', 'name')) {
                $table->dropColumn('name');
            }
            if (Schema::hasColumn('discounts', 'started_at')) {
                $table->dropColumn('started_at');
            }
            
            $table->string('title')->after('creator_id');
            $table->string('code', 64)->after('title')->unique();
            $table->enum('type', ['all_users', 'special_users'])->after('count');
        });

        Schema::table('discount_users', function (Blueprint $table) {
            if (Schema::hasColumn('discount_users', 'count')) {
                $table->dropColumn('count');
            }
        });
    }
}
