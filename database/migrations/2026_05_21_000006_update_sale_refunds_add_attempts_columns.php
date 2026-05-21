<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateSaleRefundsAddAttemptsColumns extends Migration
{
    public function up()
    {
        Schema::table('sale_refunds', function (Blueprint $table) {
            $table->unsignedInteger('attempt_count')->default(0)->after('status');
            $table->text('last_error_message')->nullable()->after('attempt_count');
            $table->timestamp('processed_at')->nullable()->after('last_error_message');
        });
    }

    public function down()
    {
        Schema::table('sale_refunds', function (Blueprint $table) {
            $table->dropColumn(['attempt_count','last_error_message','processed_at']);
        });
    }
}
