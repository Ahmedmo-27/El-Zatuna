<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\Controller;
use App\Models\RegistrationVerificationToken;
use App\User;
use Illuminate\Http\Request;

class VerifyEmailController extends Controller
{
    /**
     * Verify email via link clicked in email (DEPRECATED - keeping for backward compatibility)
     * New registrations should use code-based verification in RegisterController stepTwo
     *
     * @param Request $request
     * @param string $token
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function verify(Request $request, $token)
    {
        // This method is kept for backward compatibility with old verification links
        // New registrations use code-based verification
        
        // Verify the token
        $tokenData = RegistrationVerificationToken::verifyToken($token);
        
        if (!$tokenData) {
            // Check if API request
            if ($request->wantsJson() || $request->header('Accept') === 'application/json') {
                return apiResponse2(0, 'invalid_token', 'Verification link is invalid or expired. Please use the code sent to your email instead.');
            }
            // Web request - show error page
            return redirect('/register/step/1')->withErrors([
                'email' => trans('auth.verification_link_invalid_or_expired')
            ]);
        }

        if ($tokenData['step'] !== 2) {
            if ($request->wantsJson() || $request->header('Accept') === 'application/json') {
                return apiResponse2(0, 'invalid_step', 'This link is not valid for email verification');
            }
            return redirect('/register/step/1')->withErrors([
                'email' => trans('auth.verification_link_invalid_step')
            ]);
        }

        // Find user
        $user = User::find($tokenData['user_id']);
        
        if (!$user) {
            if ($request->wantsJson() || $request->header('Accept') === 'application/json') {
                return apiResponse2(0, 'user_not_found', 'User not found');
            }
            return redirect('/register/step/1')->withErrors([
                'email' => trans('auth.user_not_found')
            ]);
        }

        // Mark token as used
        RegistrationVerificationToken::markAsUsed($token);

        // Mark email as verified
        if (empty($user->email_verified_at)) {
            $user->update([
                'email_verified_at' => time(),
            ]);
        }

        // Generate new token for step 3 (profile completion)
        $newTokenData = [
            'user_id' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
            'step' => 3,
        ];
        
        $verificationToken = RegistrationVerificationToken::generateToken($newTokenData, 60); // 60 minutes

        // Check if request wants JSON response (API call) or HTML redirect (browser)
        if ($request->wantsJson() || $request->header('Accept') === 'application/json') {
            // API call - return JSON
            return apiResponse2(1, 'email_verified', trans('api.auth.email_verified'), [
                'verification_token' => $verificationToken,
                'expires_at' => now()->addMinutes(60)->toIso8601String(),
                'message' => 'Email verified successfully. Please complete your profile.',
                'user_id' => $user->id,
                'redirect_url' => env('FRONTEND_URL') ? env('FRONTEND_URL') . '/register/step-3?token=' . $verificationToken . '&verified=true' : null
            ]);
        }

        // Browser click - redirect to clean URL with token in session
        // Store verification token and user info in session
        session([
            'registration_step_3_token' => $verificationToken,
            'registration_verified' => true,
            'registration_user_id' => $user->id,
        ]);

        // Redirect to clean URL
        return redirect('/register/step/3')
            ->with('success', trans('auth.email_verified_successfully'));
    }

    /**
     * Resend verification code via email
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resend(Request $request)
    {
        $data = $request->all();
        
        $rules = [
            'email' => 'required|email|exists:users,email',
        ];
        
        validateParam($data, $rules);

        $user = User::where('email', $data['email'])->first();
        
        if (!$user) {
            return apiResponse2(0, 'user_not_found', 'User not found');
        }

        // Check if already verified
        if (!empty($user->email_verified_at)) {
            return apiResponse2(0, 'already_verified', 'Email is already verified');
        }

        // Generate new verification code
        $tokenData = [
            'user_id' => $user->id,
            'email' => $user->email,
            'step' => 2,
        ];
        
        $expiresAt = now()->addMinutes(60);
        $verificationCode = RegistrationVerificationToken::generateVerificationCode($tokenData, 60);

        // Send verification email with code
        $user->notify(new \App\Notifications\VerifyRegistrationEmailCode($verificationCode, $expiresAt));

        return apiResponse2(1, 'verification_resent', 'Verification code has been resent', [
            'message' => 'Please check your email for a new 6-digit verification code.',
            'expires_at' => $expiresAt->toIso8601String(),
        ]);
    }
}
