<?php

namespace App\Services;

use App\Models\CourseRevenue;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Sale;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;

class TutorRevenueService
{
    public function recordCourseSale(OrderItem $orderItem, Sale $sale): void
    {
        if (empty($orderItem->webinar_id) || $sale->type !== Order::$webinar) {
            return;
        }

        if (!empty($orderItem->subscribe_id)) {
            return;
        }

        $webinar = $orderItem->webinar;
        if (empty($webinar)) {
            return;
        }

        $tutorUser = $webinar->teacher ?: $webinar->creator;
        if (empty($tutorUser)) {
            return;
        }

        $tutor = $tutorUser->getOrCreateTutor();

        $baseAmount = max((float)$orderItem->total_amount - (float)$orderItem->tax_price, 0);
        $feePercent = (float)Setting::getFinancialSettings('tutor_platform_fee');
        $platformFee = round(($baseAmount * $feePercent) / 100, 2);
        $tutorEarnings = round($baseAmount - $platformFee, 2);

        DB::transaction(function () use ($orderItem, $tutor, $baseAmount, $platformFee, $tutorEarnings) {
            CourseRevenue::create([
                'course_id' => $orderItem->webinar_id,
                'file_id' => $orderItem->file_id ?? null,
                'tutor_id' => $tutor->id,
                'student_id' => $orderItem->user_id,
                'amount' => $baseAmount,
                'platform_fee' => $platformFee,
                'tutor_earnings' => $tutorEarnings,
                'created_at' => time(),
            ]);

            $tutor->update([
                'payout_balance' => $tutor->payout_balance + $tutorEarnings,
                'updated_at' => time(),
            ]);
        });
    }

    public function recordFileSale(OrderItem $orderItem, Sale $sale): void
    {
        if (empty($orderItem->file_id) || $sale->type !== Order::$file) {
            return;
        }

        $file = $orderItem->file;
        if (empty($file)) {
            return;
        }

        $webinar = $file->webinar;
        if (empty($webinar)) {
            return;
        }

        $tutorUser = $webinar->teacher ?: $webinar->creator;
        if (empty($tutorUser)) {
            return;
        }

        $tutor = $tutorUser->getOrCreateTutor();

        $baseAmount = max((float)$orderItem->total_amount - (float)$orderItem->tax_price, 0);
        $feePercent = (float)Setting::getFinancialSettings('tutor_platform_fee');
        $platformFee = round(($baseAmount * $feePercent) / 100, 2);
        $tutorEarnings = round($baseAmount - $platformFee, 2);

        DB::transaction(function () use ($orderItem, $tutor, $baseAmount, $platformFee, $tutorEarnings) {
            CourseRevenue::create([
                'course_id' => $orderItem->webinar_id,
                'file_id' => $orderItem->file_id,
                'tutor_id' => $tutor->id,
                'student_id' => $orderItem->user_id,
                'amount' => $baseAmount,
                'platform_fee' => $platformFee,
                'tutor_earnings' => $tutorEarnings,
                'created_at' => time(),
            ]);

            $tutor->update([
                'payout_balance' => $tutor->payout_balance + $tutorEarnings,
                'updated_at' => time(),
            ]);
        });
    }
}
