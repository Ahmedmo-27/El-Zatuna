<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*
|--------------------------------------------------------------------------
| API Version 1 (Stable) - Recommended
|--------------------------------------------------------------------------
|
| All current endpoints are now available under /api/v1/*
| This is the stable, production-ready version of the API.
|
| Request Header: Accept: application/vnd.lms.v1+json
|
*/
Route::group(['prefix' => 'v1', 'middleware' => 'api.version:v1'], function () {

    Route::get('/', function () {
        return response()->json([
            'code' => 200,
            'message' => 'LMS API v1',
            'version' => 'v1',
            'status' => 'stable',
            'documentation' => url('/api/v1/docs'),
            'migration_guide' => url('/api/v1/docs/migration'),
        ]);
    });

    Route::middleware('api')->group(base_path('routes/api/auth.php'));

    Route::namespace('Web')->group(base_path('routes/api/guest.php'));

    Route::prefix('panel')->namespace('Panel')->group(base_path('routes/api/user.php'));

    Route::group(['namespace' => 'Config', 'middleware' => []], function () {
        Route::get('/config', 'ConfigController@list');
        Route::get('/config/register/{type}', 'ConfigController@getRegisterConfig');
    });

    Route::prefix('instructor')->middleware(['api.auth', 'api.level-access:teacher'])->namespace('Instructor')->group(base_path('routes/api/instructor.php'));

    // WebSocket/Broadcasting routes
    Route::prefix('broadcasting')->middleware(['api.auth'])->namespace('Panel')->group(function () {
        Route::get('/info', ['uses' => 'BroadcastingController@getConnectionInfo']);
        Route::get('/events', ['uses' => 'BroadcastingController@getAvailableEvents']);
        Route::post('/test', ['uses' => 'BroadcastingController@testBroadcast']);
    });

    // API Documentation endpoint
    Route::get('/docs', function () {
        return response()->json([
            'version' => 'v1',
            'status' => 'stable',
            'features' => [
                'rate_limiting' => '300 requests per minute',
                'websocket' => 'Available via /broadcasting endpoints',
                'versioning' => 'Supports API versioning headers',
            ],
            'endpoints' => [
                'auth' => url('/api/v1/docs/auth'),
                'user' => url('/api/v1/docs/panel'),
                'websocket' => url('/api/v1/docs/websocket'),
                'migration' => url('/api/v1/docs/migration'),
            ],
        ]);
    });
    
    Route::get('/docs/websocket', function () {
        return response()->json([
            'title' => 'WebSocket Documentation',
            'connection_endpoint' => url('/broadcasting/auth'),
            'available_channels' => [
                'user.{userId}' => 'User-specific notifications and updates',
                'course.{courseId}' => 'Course-specific updates for enrolled students',
                'meeting.{meetingId}' => 'Meeting-specific updates',
            ],
            'events' => [
                'notification.sent',
                'message.received',
                'course.progress.updated',
                'quiz.graded',
                'meeting.started',
                'support.ticket.replied',
            ],
            'setup_guide' => [
                '1. Get connection info: GET /api/v1/broadcasting/info',
                '2. Connect to WebSocket using your preferred library (Pusher, Socket.io, etc.)',
                '3. Subscribe to channels: private-user.{your_user_id}',
                '4. Listen for events listed above',
            ],
        ]);
    });

    // Migration guide endpoint
    Route::get('/docs/migration', function () {
        return response()->json([
            'title' => 'API Migration Guide',
            'from_version' => 'development',
            'to_version' => 'v1',
            'changes' => [
                'All endpoints now use /api/v1/* prefix',
                'Versioning support via Accept header',
                'Consistent response format across all endpoints',
            ],
            'breaking_changes' => [],
            'deprecated_endpoints' => [],
            'guide_url' => url('/docs/API_MIGRATION_GUIDE.md'),
        ]);
    });

});

/*
|--------------------------------------------------------------------------
| API Version 2 (Future)
|--------------------------------------------------------------------------
|
| Future version for breaking changes and new features.
| Currently in planning phase.
|
*/
Route::group(['prefix' => 'v2', 'middleware' => 'api.version:v2'], function () {
    Route::get('/', function () {
        return response()->json([
            'code' => 200,
            'message' => 'API v2',
            'version' => 'v2',
            'status' => 'Coming soon',
            'planned_features' => [
                'Standardized terminology (course instead of webinar)',
                'Enhanced pagination support',
                'Improved error responses',
            ],
            'migration_guide' => url('/api/v2/docs/migration'),
        ]);
    });

    Route::get('/docs/migration', function () {
        return response()->json([
            'title' => 'API v2 Migration Guide',
            'from_version' => 'v1',
            'to_version' => 'v2',
            'status' => 'Planning phase',
            'planned_changes' => [
                'webinar → course terminology',
                'Enhanced pagination across all list endpoints',
                'Improved WebSocket documentation',
            ],
            'release_date' => 'TBD',
        ]);
    });
});


