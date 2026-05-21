<?php
namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Services\LiveSessionManagementService;
use Illuminate\Http\Request;
use App\Models\LiveSession;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;

/**
 * @OA\Tag(
 *     name="Teacher Live Sessions",
 *     description="Teacher live-session management pages"
 * )
 */

class LiveSessionController extends Controller
{
    /**
     * List sessions created by the authenticated teacher.
     *
     * @OA\Get(
     *     path="/teacher/live-sessions",
     *     summary="List teacher live sessions",
     *     tags={"Teacher Live Sessions"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Teacher live sessions list")
     * )
     */
    public function index()
    {
        $teacherId = Auth::id();
        $sessions = LiveSession::where('creator_id', $teacherId)
            ->orderBy('created_at', 'desc')
            ->paginate(12);
        return view('teacher.live-sessions.index', compact('sessions'));
    }

    /**
     * Show the create form.
     *
     * @OA\Get(
     *     path="/teacher/live-sessions/create",
     *     summary="Create live session form",
     *     tags={"Teacher Live Sessions"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Create form")
     * )
     */
    public function create()
    {
        return view('teacher.live-sessions.create');
    }

    /**
     * Store a new live session (draft by default).
     *
     * @OA\Post(
     *     path="/teacher/live-sessions",
     *     summary="Store live session",
     *     tags={"Teacher Live Sessions"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=302, description="Redirect after create")
     * )
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'university_id' => 'required|integer',
            'faculty_id' => 'required|integer',
            'start_at' => 'required|date',
            'end_at' => 'required|date|after:start_at',
            'price' => 'required|numeric|min:0',
            'max_students' => 'required|integer|min:1',
            'provider_type' => 'required|in:manual_zoom,manual_meet',
            'provider_url' => 'required|url',
            'instructions' => 'nullable|string',
        ]);
        $data['creator_id'] = Auth::id();
        $data['provider'] = $data['provider_type'];
        unset($data['provider_type']);
        $data['status'] = 'draft';
        LiveSession::create($data);
        return redirect()->route('teacher.live_sessions.index')
            ->with('success', 'Live session created as draft.');
    }

    /**
     * Show session details for the teacher.
     *
     * @OA\Get(
     *     path="/teacher/live-sessions/{id}",
     *     summary="Show teacher live session",
     *     tags={"Teacher Live Sessions"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Teacher live session details")
     * )
     */
    public function show($id)
    {
        $session = LiveSession::with('bookings.student')->findOrFail($id);
        $this->authorize('view', $session);
        return view('teacher.live-sessions.show', compact('session'));
    }

    /**
     * Show edit form – only allowed for draft or when no bookings exist.
     *
     * @OA\Get(
     *     path="/teacher/live-sessions/{id}/edit",
     *     summary="Edit live session form",
     *     tags={"Teacher Live Sessions"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Edit form")
     * )
     */
    public function edit($id)
    {
        $session = LiveSession::findOrFail($id);
        $this->authorize('update', $session);
        return view('teacher.live-sessions.edit', compact('session'));
    }

    /**
     * Update a session.
     *
     * @OA\Put(
     *     path="/teacher/live-sessions/{id}",
     *     summary="Update live session",
     *     tags={"Teacher Live Sessions"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=302, description="Redirect after update")
     * )
     */
    public function update(Request $request, $id)
    {
        $session = LiveSession::findOrFail($id);
        $this->authorize('update', $session);
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'university_id' => 'required|integer',
            'faculty_id' => 'required|integer',
            'start_at' => 'required|date',
            'end_at' => 'required|date|after:start_at',
            'price' => 'required|numeric|min:0',
            'max_students' => 'required|integer|min:1',
            'provider_type' => 'required|in:manual_zoom,manual_meet',
            'provider_url' => 'required|url',
            'instructions' => 'nullable|string',
        ];
        // Prevent status change via this endpoint – publishing uses a dedicated action.
        $data = $request->validate($rules);
        $data['provider'] = $data['provider_type'];
        unset($data['provider_type']);
        $session->update($data);
        return redirect()->route('teacher.live_sessions.show', $session->id)
            ->with('success', 'Live session updated.');
    }

    /**
     * Publish a draft session.
     *
     * @OA\Post(
     *     path="/teacher/live-sessions/{id}/publish",
     *     summary="Publish live session",
     *     tags={"Teacher Live Sessions"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=302, description="Redirect after publish")
     * )
     */
    public function publish($id)
    {
        $session = LiveSession::findOrFail($id);
        $this->authorize('publish', $session);
        app(LiveSessionManagementService::class)->publish($session);
        return redirect()->route('teacher.live_sessions.show', $session->id)
            ->with('success', 'Live session published.');
    }

    /**
     * Cancel a session (teacher).
     *
     * @OA\Post(
     *     path="/teacher/live-sessions/{id}/cancel",
     *     summary="Cancel live session",
     *     tags={"Teacher Live Sessions"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=302, description="Redirect after cancel")
     * )
     */
    public function cancel($id)
    {
        $session = LiveSession::findOrFail($id);
        $this->authorize('cancel', $session);
        app(LiveSessionManagementService::class)->cancel($session);
        return redirect()->route('teacher.live_sessions.index')
            ->with('success', 'Live session cancelled.');
    }
}
