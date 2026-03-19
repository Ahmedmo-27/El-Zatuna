<?php

namespace App\Http\Controllers\Api\Panel;

use App\Http\Controllers\Api\Controller;
use Illuminate\Http\Request;
use App\Events\NotificationSent;
use App\Events\MessageReceived;

class BroadcastingController extends Controller
{
    /**
     * Get WebSocket connection info (channels, driver config).
     *
     * @OA\Get(
     *     path="/v1/broadcasting/info",
     *     summary="Get broadcasting connection info",
     *     tags={"Broadcasting"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Connection info (channels, pusher key/cluster if applicable)"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getConnectionInfo(Request $request)
    {
        $user = apiAuth();

        if (!$user) {
            return apiResponse2(0, 'unauthorized', trans('auth.not_login'));
        }

        $config = config('broadcasting.connections.' . config('broadcasting.default'));
        
        $connectionInfo = [
            'enabled' => config('broadcasting.default') !== 'null',
            'driver' => config('broadcasting.default'),
            'channels' => [
                'user' => 'private-user.' . $user->id,
                'notifications' => 'private-user.' . $user->id,
                'messages' => 'private-user.' . $user->id,
            ],
        ];

        // Add driver-specific info
        if (config('broadcasting.default') === 'pusher') {
            $connectionInfo['pusher'] = [
                'key' => $config['key'] ?? null,
                'cluster' => $config['options']['cluster'] ?? null,
                'encrypted' => true,
            ];
        }

        return apiResponse2(1, 'retrieved', 'WebSocket connection info retrieved', $connectionInfo);
    }

    /**
     * Send a test broadcast notification to the current user's channel.
     *
     * @OA\Post(
     *     path="/v1/broadcasting/test",
     *     summary="Test broadcast",
     *     tags={"Broadcasting"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Test notification sent"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function testBroadcast(Request $request)
    {
        $user = apiAuth();

        if (!$user) {
            return apiResponse2(0, 'unauthorized', trans('auth.not_login'));
        }

        $notification = [
            'id' => time(),
            'title' => 'Test Notification',
            'message' => 'This is a test broadcast notification',
            'type' => 'info',
            'created_at' => now()->toIso8601String(),
        ];

        // Broadcast the event
        try {
            broadcast(new NotificationSent($user->id, $notification));

            return apiResponse2(1, 'broadcast_sent', 'Test broadcast sent successfully', [
                'channel' => 'private-user.' . $user->id,
                'event' => 'notification.sent',
                'data' => $notification,
            ]);
        } catch (\Exception $e) {
            return apiResponse2(0, 'broadcast_failed', 'Failed to send broadcast', [
                'error' => config('app.debug') ? $e->getMessage() : 'Broadcasting not configured',
            ]);
        }
    }

    /**
     * Get list of available WebSocket events and their payloads.
     *
     * @OA\Get(
     *     path="/v1/broadcasting/events",
     *     summary="Get available broadcast events",
     *     tags={"Broadcasting"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Events (notification.sent, message.received, course.progress.updated, quiz.graded, meeting.started, support.ticket.replied)"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAvailableEvents(Request $request)
    {
        $events = [
            'notification.sent' => [
                'description' => 'Triggered when a new notification is sent',
                'channel' => 'private-user.{userId}',
                'data' => [
                    'notification_id' => 'integer',
                    'title' => 'string',
                    'message' => 'string',
                    'type' => 'string (info|success|warning|error)',
                    'created_at' => 'ISO8601 timestamp',
                ],
            ],
            'message.received' => [
                'description' => 'Triggered when a new message is received',
                'channel' => 'private-user.{userId}',
                'data' => [
                    'message_id' => 'integer',
                    'sender_id' => 'integer',
                    'sender_name' => 'string',
                    'content' => 'string',
                    'created_at' => 'ISO8601 timestamp',
                ],
            ],
            'course.progress.updated' => [
                'description' => 'Triggered when course progress is updated',
                'channel' => 'private-user.{userId}',
                'data' => [
                    'course_id' => 'integer',
                    'progress' => 'integer (0-100)',
                    'updated_at' => 'ISO8601 timestamp',
                ],
            ],
            'quiz.graded' => [
                'description' => 'Triggered when a quiz is graded',
                'channel' => 'private-user.{userId}',
                'data' => [
                    'quiz_id' => 'integer',
                    'score' => 'integer',
                    'passed' => 'boolean',
                    'graded_at' => 'ISO8601 timestamp',
                ],
            ],
            'meeting.started' => [
                'description' => 'Triggered when a meeting starts',
                'channel' => 'private-user.{userId}',
                'data' => [
                    'meeting_id' => 'integer',
                    'join_url' => 'string',
                    'started_at' => 'ISO8601 timestamp',
                ],
            ],
            'support.ticket.replied' => [
                'description' => 'Triggered when a support ticket receives a reply',
                'channel' => 'private-user.{userId}',
                'data' => [
                    'ticket_id' => 'integer',
                    'message' => 'string',
                    'replied_at' => 'ISO8601 timestamp',
                ],
            ],
        ];

        return apiResponse2(1, 'retrieved', 'Available WebSocket events', [
            'events' => $events,
            'connection_guide' => url('/api/v1/docs/websocket'),
        ]);
    }
}
