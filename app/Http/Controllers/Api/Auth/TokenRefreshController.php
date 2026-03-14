<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class TokenRefreshController extends Controller
{
    /**
     * Refresh JWT token.
     *
     * @OA\Post(
     *     path="/v1/auth/refresh",
     *     summary="Refresh token",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"refresh_token"},
     *             @OA\Property(property="refresh_token", type="string", description="Current JWT or refresh token")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Token refreshed", @OA\JsonContent(
     *         @OA\Property(property="success", type="boolean", example=true),
     *         @OA\Property(property="status", type="string", example="token_refreshed"),
     *         @OA\Property(property="data", type="object",
     *             @OA\Property(property="access_token", type="string"),
     *             @OA\Property(property="token_type", type="string", example="bearer"),
     *             @OA\Property(property="expires_at", type="string", format="date-time")
     *         )
     *     )),
     *     @OA\Response(response=200, description="Error (token_expired, token_invalid)")
     * )
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh(Request $request)
    {
        try {
            // Validate the request
            $rules = [
                'refresh_token' => 'required|string',
            ];
            
            validateParam($request->all(), $rules);

            // Get the old token from the request
            $oldToken = $request->input('refresh_token');

            // Set the token for authentication
            JWTAuth::setToken($oldToken);

            // Refresh the token
            $newToken = JWTAuth::refresh($oldToken);

            // Get token TTL from config (default to 60 minutes if not set)
            $ttl = config('jwt.ttl', 60);
            $expiresAt = now()->addMinutes($ttl)->toIso8601String();

            return apiResponse2(1, 'token_refreshed', trans('auth.token_refreshed'), [
                'access_token' => $newToken,
                'token_type' => 'bearer',
                'expires_at' => $expiresAt,
                'refresh_token' => $newToken, // In JWT, the same token can be used for refresh
            ]);

        } catch (TokenExpiredException $e) {
            return apiResponse2(0, 'token_expired', trans('auth.token_expired'));
        } catch (TokenInvalidException $e) {
            return apiResponse2(0, 'token_invalid', trans('auth.token_invalid'));
        } catch (JWTException $e) {
            return apiResponse2(0, 'token_refresh_failed', trans('auth.token_refresh_failed'));
        } catch (\Exception $e) {
            return apiResponse2(0, 'error', $e->getMessage());
        }
    }
}
