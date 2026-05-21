<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateLiveSessionBookingsAddRefsAndUniques extends Migration
{
    public function up()
    {
        Schema::table('live_session_bookings', function (Blueprint $table) {
            $table->string('payment_reference')->nullable()->after('sale_id');
            $table->string('refund_reference')->nullable()->after('payment_reference');
            $table->string('gateway_name')->nullable()->after('refund_reference');
            $table->string('gateway_event_id')->nullable()->after('gateway_name');
            $table->enum('refund_status', ['pending','processed','failed'])->default('pending')->after('refund_reference');

            $table->unique('payment_reference');
            $table->unique('refund_reference');
            $table->unique(['gateway_name','gateway_event_id']);
            $table->unique(['live_session_id','student_id']);
        });
    }

    public function down()
    {
        Schema::table('live_session_bookings', function (Blueprint $table) {
            $table->dropUnique(['payment_reference']);
            $table->dropUnique(['refund_reference']);
            $table->dropUnique(['gateway_name','gateway_event_id']);
            $table->dropUnique(['live_session_id','student_id']);

            $table->dropColumn(['payment_reference','refund_reference','gateway_name','gateway_event_id','refund_status']);
        });
    }
}
