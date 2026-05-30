<?php

namespace App\Http\Controllers\Web\traits;

use App\Models\TimeSpentOnCourse;
use App\Models\Webinar;
use App\Models\WebinarChapter;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Request;

trait LearningPageMixinsTrait
{
    public function getCourse($slug, $user = null, $relation = null, $relationWith = null)
    {
        if (empty($user)) {
            $user = auth()->user();
        }

        $query = Webinar::where('slug', $slug)
            ->where('status', 'active');

        if (!empty($relation)) {
            $query->with([
                "{$relation}" => function ($query) use ($relation, $relationWith) {
                    if ($relation == 'forums') {
                        $query->orderBy('pin', 'desc');
                    }

                    $query->orderBy('created_at', 'desc');

                    if (!empty($relationWith)) {
                        $query->with($relationWith);
                    }
                }
            ])->withCount([
                "{$relation}"
            ]);
        }

        $query->with([
            'chapters' => function ($query) use ($user) {
                $query->where('status', WebinarChapter::$chapterActive);
                $query->orderBy('order', 'asc');

                $query->with([
                    'chapterItems' => function ($query) {
                        $query->orderBy('order', 'asc');
                    }
                ]);
            }
        ]);

        $course = $query->first();

        if (empty($course)) {
            return 'not_access';
        }

        // Must match LearningPageController@index + WebinarController::course access so APIs (track-time, etc.)
        // work for anyone who can open the learning page (including free first section / preview users).
        $hasBought = $course->checkUserHasBought($user, true, true);
        $hasInstallment = !empty($course->getInstallmentOrder());
        $canAccessFirstSectionFree = !$hasBought && !$hasInstallment && !empty($user) && $course->chapters && $course->chapters->count() > 0;

        if (!$hasBought && !$hasInstallment && !$canAccessFirstSectionFree) {
            return 'not_access';
        }

        $isPrivate = $course->private;

        if (!empty($user) and ($user->id == $course->creator_id or $user->organ_id == $course->creator_id or $user->isAdmin() or $hasBought)) {
            $isPrivate = false;
        }

        if ($isPrivate) {
            return 'not_access';
        }

        return $course;
    }

    private function handleStartTrackingTime($courseId, $userId)
    {
        $time = time();

        TimeSpentOnCourse::query()->create([
            'user_id' => $userId,
            'course_id' => $courseId,
            'page' => "learning_page",
            'entry_time' => $time,
            'exit_time' => $time + 10, // After entering the page, we record the last time every 10 seconds. So at the beginning, we also record the exit time 10 seconds earlier.
            'seconds_spent' => 10,
        ]);
    }

    public function trackSpentTime(Request $request, $courseSlug)
    {
        $course = $this->getCourse($courseSlug);

        if ($course == 'not_access') {
            abort(404);
        }

        $user = auth()->user();

        $trackingTime = TimeSpentOnCourse::query()->where('course_id', $course->id)
            ->where('user_id', $user->id)
            ->orderBy('entry_time', 'desc')
            ->first();

        $forceReload = true;

        if (!empty($trackingTime)) {
            $forceReload = false;
            $time = time();
            $exitTime = $time + 10;
            $secondsSpent = $exitTime - $trackingTime->entry_time;

            $trackingTime->update([
                'exit_time' => $exitTime,
                'seconds_spent' => $secondsSpent,
            ]);
        }

        return response()->json([
            'code' => 200,
            'force_reload' => $forceReload,
        ]);
    }

    public function trackVideoPerformance(Request $request, string $courseSlug): JsonResponse
    {
        $course = $this->getCourse($courseSlug);

        if ($course == 'not_access') {
            abort(404);
        }

        $user = auth()->user();

        $validated = $request->validate([
            'file_id' => 'required|integer|min:1',
            'stalls' => 'nullable|integer|min:0|max:100000',
            'recoveries' => 'nullable|integer|min:0|max:100000',
            'buffer_events' => 'nullable|integer|min:0|max:100000',
            'total_recovery_ms' => 'nullable|numeric|min:0|max:999999999',
            'avg_recovery_ms' => 'nullable|numeric|min:0|max:999999',
            'max_recovery_ms' => 'nullable|numeric|min:0|max:999999',
            'playback_seconds' => 'nullable|numeric|min:0|max:9999999',
            'last_position' => 'nullable|numeric|min:0|max:9999999',
            'source' => 'nullable|string|max:64',
        ]);

        \Log::info('learning_video_performance', [
            'user_id' => $user->id,
            'course_id' => $course->id,
            'course_slug' => $courseSlug,
            'file_id' => (int) ($validated['file_id'] ?? 0),
            'stalls' => (int) ($validated['stalls'] ?? 0),
            'recoveries' => (int) ($validated['recoveries'] ?? 0),
            'buffer_events' => (int) ($validated['buffer_events'] ?? 0),
            'total_recovery_ms' => (float) ($validated['total_recovery_ms'] ?? 0),
            'avg_recovery_ms' => (float) ($validated['avg_recovery_ms'] ?? 0),
            'max_recovery_ms' => (float) ($validated['max_recovery_ms'] ?? 0),
            'playback_seconds' => (float) ($validated['playback_seconds'] ?? 0),
            'last_position' => (float) ($validated['last_position'] ?? 0),
            'source' => (string) ($validated['source'] ?? 'learning_page'),
            'ip' => $request->ip(),
            'ua' => (string) $request->userAgent(),
        ]);

        return response()->json([
            'code' => 200,
        ]);
    }
}
