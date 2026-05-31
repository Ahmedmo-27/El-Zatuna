<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('admin_logs_activity');

        $query = Activity::query();
        $query = $this->filters($query, $request);

        $activities = $query->latest()
            ->with(['causer', 'subject'])
            ->paginate(20);

        $logNames = Activity::query()
            ->select('log_name')
            ->whereNotNull('log_name')
            ->distinct()
            ->orderBy('log_name')
            ->pluck('log_name');

        $events = ['created', 'updated', 'deleted', 'restored'];

        $data = [
            'pageTitle' => trans('logs.activity_logs'),
            'activities' => $activities,
            'logNames' => $logNames,
            'events' => $events,
        ];

        return view('admin.logs.activity.index', $data);
    }

    private function filters($query, Request $request)
    {
        $search = $request->get('search');
        $event = $request->get('event');
        $logName = $request->get('log_name');
        $from = $request->get('from');
        $to = $request->get('to');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%$search%")
                    ->orWhereHasMorph('causer', [User::class], function ($q) use ($search) {
                        $q->where('full_name', 'like', "%$search%")
                            ->orWhere('email', 'like', "%$search%");
                    });
            });
        }

        if (!empty($event)) {
            $query->where('event', $event);
        }

        if (!empty($logName)) {
            $query->where('log_name', $logName);
        }

        if (!empty($from)) {
            $query->whereDate('created_at', '>=', $from);
        }

        if (!empty($to)) {
            $query->whereDate('created_at', '<=', $to);
        }

        return $query;
    }

    public function destroy(Request $request, $id)
    {
        $this->authorize('admin_logs_activity_delete');

        $activity = Activity::findOrFail($id);
        $activity->delete();

        return $this->successResponse($request, trans('logs.record_deleted'));
    }

    public function clearOld(Request $request)
    {
        $this->authorize('admin_logs_activity_delete');

        $deleted = Activity::query()
            ->where('created_at', '<', now()->subDays(30))
            ->delete();

        return $this->successResponse($request, trans('logs.records_cleared', ['count' => $deleted]));
    }

    private function successResponse(Request $request, string $msg)
    {
        $title = trans('public.request_success');

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'code' => 200,
                'title' => $title,
                'msg' => $msg,
            ], 200);
        }

        return back()->with(['toast' => [
            'title' => $title,
            'msg' => $msg,
            'status' => 'success',
        ]]);
    }
}
