<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLiveSessionActivityLogs extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('live_session_activity_logs')) {
            Schema::create('live_session_activity_logs', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('live_session_id')->index();
                $table->unsignedBigInteger('user_id')->nullable()->index();
                $table->unsignedBigInteger('booking_id')->nullable()->index();
                $table->string('action');
                $table->text('description')->nullable();
                $table->json('payload')->nullable();
                $table->timestamps();

                $table->foreign('live_session_id')->references('id')->on('live_sessions')->onDelete('cascade');
            });

            return;
        }

        $addedLiveSessionId = false;

        Schema::table('live_session_activity_logs', function (Blueprint $table) use (&$addedLiveSessionId) {
            if (!Schema::hasColumn('live_session_activity_logs', 'live_session_id')) {
                $table->unsignedBigInteger('live_session_id')->nullable()->index();
                $addedLiveSessionId = true;
            }

            if (!Schema::hasColumn('live_session_activity_logs', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->index();
            }

            if (!Schema::hasColumn('live_session_activity_logs', 'booking_id')) {
                $table->unsignedBigInteger('booking_id')->nullable()->index();
            }

            if (!Schema::hasColumn('live_session_activity_logs', 'action')) {
                $table->string('action');
            }

            if (!Schema::hasColumn('live_session_activity_logs', 'description')) {
                $table->text('description')->nullable();
            }

            if (!Schema::hasColumn('live_session_activity_logs', 'payload')) {
                $table->json('payload')->nullable();
            }

            if (!Schema::hasColumn('live_session_activity_logs', 'created_at')) {
                $table->timestamps();
            }
        });

        if ($addedLiveSessionId) {
            Schema::table('live_session_activity_logs', function (Blueprint $table) {
                $table->foreign('live_session_id')->references('id')->on('live_sessions')->onDelete('cascade');
            });
        }
    }

    public function down()
    {
        if (!Schema::hasTable('live_session_activity_logs')) {
            return;
        }

        Schema::table('live_session_activity_logs', function (Blueprint $table) {
            if (Schema::hasColumn('live_session_activity_logs', 'live_session_id')) {
                $table->dropForeign(['live_session_id']);
                $table->dropColumn('live_session_id');
            }

            $columns = [];

            foreach (['user_id', 'booking_id', 'action', 'description', 'payload'] as $column) {
                if (Schema::hasColumn('live_session_activity_logs', $column)) {
                    $columns[] = $column;
                }
            }

            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
}
