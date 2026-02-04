<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\Auth\VerificationController;
use App\Http\Controllers\Api\Controller;
use App\Http\Controllers\Web\traits\UserFormFieldsTrait;
use App\Mixins\RegistrationBonus\RegistrationBonusAccounting;
use App\Models\Affiliate;
use App\Models\Reward;
use App\Models\RewardAccounting;
use App\Models\Role;
use App\Models\UserFormField;
use App\Models\UserMeta;
use App\Models\RegistrationVerificationToken;
use App\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller
{
    use UserFormFieldsTrait;
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */
    public function stepRegister(Request $request, $step)
    {
        if ($step == 1) {
            return $this->stepOne($request);

        } elseif ($step == 2) {
            return $this->stepTwo($request);

        } elseif ($step == 3) {
            return $this->stepThree($request);
        }
        abort(404);

    }

    private function stepOne(Request $request)
    {
        $data = $request->all();

        // Step 1: Initial registration - collect full_name and email only
        $rules = [
            'full_name' => 'required|string|min:3',
            'email' => 'required|string|email|max:255|unique:users',
        ];

        validateParam($data, $rules);

        // Check if user already exists
        $userCase = User::where('email', $data['email'])
            ->first();

        if ($userCase) {
            if ($userCase->username && $userCase->status == User::$active) {
                return apiResponse2(0, 'already_registered', trans('api.auth.already_registered'));
            } else {
                // User exists but incomplete - generate new token for step 3
                $tokenData = [
                    'user_id' => $userCase->id,
                    'email' => $userCase->email,
                    'step' => 3,
                ];
                
                $verificationToken = RegistrationVerificationToken::generateToken($tokenData, 60); // 60 minutes
                
                return apiResponse2(1, 'go_step_3', trans('api.auth.go_step_3'), [
                    'verification_token' => $verificationToken,
                    'expires_at' => now()->addMinutes(60)->toIso8601String(),
                ]);
            }
        }

        $disableRegistrationVerificationProcess = getGeneralOptionsSettings('disable_registration_verification_process');

        if ($disableRegistrationVerificationProcess) {
            // If verification is disabled, create user directly and skip to step 3
            $referralSettings = getReferralSettings();
            $usersAffiliateStatus = (!empty($referralSettings) and !empty($referralSettings['users_affiliate_status']));

            $user = User::create([
                'role_name' => Role::$user,
                'role_id' => Role::getUserRoleId(),
                'full_name' => $data['full_name'],
                'email' => $data['email'],
                'status' => User::$pending,
                'password' => Hash::make(Str::random(32)), // Temporary password, will be set in step 3
                'affiliate' => $usersAffiliateStatus,
                'created_at' => time()
            ]);

            if (!empty($data['certificate_additional'])) {
                UserMeta::updateOrCreate([
                    'user_id' => $user->id,
                    'name' => 'certificate_additional'
                ], [
                    'value' => $data['certificate_additional']
                ]);
            }

            $form = $this->getFormFieldsByType($request->get('account_type'));
            $this->storeFormFields($data, $user);

            // Generate token for step 3 (profile completion)
            $tokenData = [
                'user_id' => $user->id,
                'email' => $user->email,
                'step' => 3,
            ];
            
            $verificationToken = RegistrationVerificationToken::generateToken($tokenData, 60); // 60 minutes

            return apiResponse2(1, 'go_step_3', trans('api.public.stored') . ' Please complete your profile.', [
                'verification_token' => $verificationToken,
                'expires_at' => now()->addMinutes(60)->toIso8601String(),
            ]);
        }

        // Create user and send email verification
        $referralSettings = getReferralSettings();
        $usersAffiliateStatus = (!empty($referralSettings) and !empty($referralSettings['users_affiliate_status']));

        $user = User::create([
            'role_name' => Role::$user,
            'role_id' => Role::getUserRoleId(),
            'full_name' => $data['full_name'],
            'email' => $data['email'],
            'status' => User::$pending,
            'password' => Hash::make(Str::random(32)), // Temporary password, will be set in step 3
            'affiliate' => $usersAffiliateStatus,
            'created_at' => time()
        ]);

        if (!empty($data['certificate_additional'])) {
            UserMeta::updateOrCreate([
                'user_id' => $user->id,
                'name' => 'certificate_additional'
            ], [
                'value' => $data['certificate_additional']
            ]);
        }

        $form = $this->getFormFieldsByType($request->get('account_type'));
        $this->storeFormFields($data, $user);

        // Generate token for step 2 (email verification)
        $tokenData = [
            'user_id' => $user->id,
            'email' => $user->email,
            'step' => 2,
        ];
        
        $expiresAt = now()->addMinutes(60);
        $verificationToken = RegistrationVerificationToken::generateToken($tokenData, 60); // 60 minutes

        // Send verification email
        $user->notify(new \App\Notifications\VerifyRegistrationEmail($verificationToken, $expiresAt));

        return apiResponse2(1, 'verification_sent', trans('api.auth.verification_sent'), [
            'message' => 'Please check your email to verify your account.',
            'expires_at' => $expiresAt->toIso8601String(),
        ]);
    }

    private function stepTwo(Request $request)
    {
        $data = $request->all();
        
        // Step 2: Verify email with token
        $rules = [
            'verification_token' => 'required|string',
        ];
        
        validateParam($data, $rules);

        // Verify the token
        $tokenData = RegistrationVerificationToken::verifyToken($data['verification_token']);
        
        if (!$tokenData) {
            return apiResponse2(0, 'invalid_token', 'Verification token is invalid or expired');
        }

        if ($tokenData['step'] !== 2) {
            return apiResponse2(0, 'invalid_step', 'This token is not valid for step 2');
        }

        // Find user
        $user = User::find($tokenData['user_id']);
        
        if (!$user) {
            return apiResponse2(0, 'user_not_found', 'User not found');
        }

        // Mark token as used
        RegistrationVerificationToken::markAsUsed($data['verification_token']);

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

        return apiResponse2(1, 'email_verified', trans('api.auth.email_verified'), [
            'verification_token' => $verificationToken,
            'expires_at' => now()->addMinutes(60)->toIso8601String(),
            'message' => 'Email verified successfully. Please complete your profile.'
        ]);
    }

    private function stepThree(Request $request)
    {
        $data = $request->all();
        
        // Step 3: Complete profile with username, password, university, faculty, and optional referral code
        $rules = [
            'verification_token' => 'required|string',
            'username' => 'required|string|min:3|max:255|unique:users',
            'password' => ['required', 'string', 'confirmed', new \App\Rules\StrongPassword($data['username'] ?? '')],
            'password_confirmation' => 'required|same:password',
            'university_id' => 'required|exists:universities,id',
            'faculty_id' => [
                'required',
                Rule::exists('faculties', 'id')->where(function ($query) use ($data) {
                    $query->where('university_id', $data['university_id'] ?? null);
                })
            ],
            'referral_code' => 'nullable|exists:affiliates_codes,code'
        ];
        
        validateParam($data, $rules);

        // Verify the token
        $tokenData = RegistrationVerificationToken::verifyToken($data['verification_token']);
        
        if (!$tokenData) {
            return apiResponse2(0, 'invalid_token', 'Verification token is invalid or expired');
        }

        if ($tokenData['step'] !== 3) {
            return apiResponse2(0, 'invalid_step', 'This token is not valid for step 3');
        }

        // Find user by user_id or email from token
        $user = null;
        if (!empty($tokenData['user_id'])) {
            $user = User::find($tokenData['user_id']);
        } elseif (!empty($tokenData['email'])) {
            $user = User::where('email', $tokenData['email'])->first();
        }

        if (!$user) {
            return apiResponse2(0, 'user_not_found', 'User not found. Please complete step 1 first.');
        }

        // Update user profile with username, password, university, and faculty
        $user->update([
            'username' => $data['username'],
            'password' => Hash::make($data['password']),
            'university_id' => $data['university_id'],
            'faculty_id' => $data['faculty_id'],
            'status' => User::$active, // Activate user now
        ]);

        // Mark token as used
        RegistrationVerificationToken::markAsUsed($data['verification_token']);

        // Registration bonus
        $enableRegistrationBonus = false;
        $registrationBonusAmount = null;
        $registrationBonusSettings = getRegistrationBonusSettings();
        if (!empty($registrationBonusSettings['status']) and !empty($registrationBonusSettings['registration_bonus_amount'])) {
            $enableRegistrationBonus = true;
            $registrationBonusAmount = $registrationBonusSettings['registration_bonus_amount'];
        }

        $user->update([
            'enable_registration_bonus' => $enableRegistrationBonus,
            'registration_bonus_amount' => $registrationBonusAmount,
        ]);

        // Rewards
        $registerReward = RewardAccounting::calculateScore(Reward::REGISTER);
        RewardAccounting::makeRewardAccounting($user->id, $registerReward, Reward::REGISTER, $user->id, true);
        
        $registrationBonusAccounting = new RegistrationBonusAccounting();
        $registrationBonusAccounting->storeRegistrationBonusInstantly($user);
        
        // Handle referral code
        $referralCode = $request->input('referral_code', null);
        if (!empty($referralCode)) {
            Affiliate::storeReferral($user, $referralCode);
        }
        
        event(new Registered($user));
        
        // Generate JWT token
        $token = auth('api')->tokenById($user->id);
        
        return apiResponse2(1, 'registered', trans('api.auth.login'), [
            'token' => $token,
            'user_id' => $user->id,
            'expires_at' => now()->addMinutes(config('jwt.ttl', 60))->toIso8601String(),
        ]);
    }

    public function username()
    {
        $email_regex = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i";

        $data = request()->all();

        if (empty($this->username)) {
            if (in_array('mobile', array_keys($data))) {
                $this->username = 'mobile';
            } else if (in_array('email', array_keys($data))) {
                $this->username = 'email';
            }
        }

        return $this->username ?? '';
    }


}
