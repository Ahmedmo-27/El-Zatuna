<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    //   use ResetsPasswords;

    /**
     * Complete password reset with token from email.
     *
     * @OA\Post(
     *     path="/v1/auth/reset-password/{token}",
     *     summary="Reset password",
     *     tags={"Auth"},
     *     @OA\Parameter(name="token", in="path", required=true, @OA\Schema(type="string")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password","password_confirmation"},
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="password", type="string"),
     *             @OA\Property(property="password_confirmation", type="string")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Password reset", @OA\JsonContent(
     *         @OA\Property(property="success", type="boolean", example=true),
     *         @OA\Property(property="status", type="string", example="password_reset")
     *     )),
     *     @OA\Response(response=200, description="Not found", @OA\JsonContent(
     *         @OA\Property(property="success", type="boolean", example=false),
     *         @OA\Property(property="status", type="string", example="not_found")
     *     ))
     * )
     */
    public function updatePassword(Request $request,$token)
    {

        validateParam($request->all(), [
            'email' => 'required|email|exists:users',
            'password' => ['required', 'string', 'confirmed', new \App\Rules\StrongPassword()],
            'password_confirmation' => 'required',
        ]);

        $data = $request->all();

        $updatePassword = DB::table('password_resets')
         //   ->where(['email' => $data['email'], 'token' => $data['token']])
            ->where(['email' => $data['email'], 'token' => $token])
            ->first();

        if (!empty($updatePassword)) {
            $user = User::where('email', $data['email'])
                ->update(['password' => Hash::make($data['password'])]);
            DB::table('password_resets')->where(['email' => $data['email']])->delete();

           return apiResponse2(1, 'password_reset', 'password reset.');
        }
        return apiResponse2(0, 'not_found', 'there is not such request to reset password');

    }
}
