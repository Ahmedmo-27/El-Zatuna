<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// User-specific private channels
Broadcast::channel('user.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});

// Notifications channel
Broadcast::channel('notifications.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});

// Messages channel
Broadcast::channel('messages.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});

// Course-specific channels (for enrolled students)
Broadcast::channel('course.{courseId}', function ($user, $courseId) {
    // Check if user is enrolled in the course
    return $user->webinars()->where('id', $courseId)->exists();
});

// Meeting channels (for meeting participants)
Broadcast::channel('meeting.{meetingId}', function ($user, $meetingId) {
    // Check if user is a participant
    return \App\Models\ReserveMeeting::where('id', $meetingId)
        ->where(function($query) use ($user) {
            $query->where('user_id', $user->id)
                  ->orWhereHas('meeting', function($q) use ($user) {
                      $q->where('creator_id', $user->id);
                  });
        })
        ->exists();
});
