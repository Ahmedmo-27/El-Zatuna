<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Web\traits\UserFormFieldsTrait;
use App\Mixins\RegistrationBonus\RegistrationBonusAccounting;
use App\Models\Affiliate;
use App\Models\Faculty;
use App\Models\Reward;
use App\Models\RewardAccounting;
use App\Models\Role;
use App\Models\University;
use App\Models\UserMeta;
use App\Models\RegistrationVerificationToken;
use App\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class RegisterController extends Controller
{

    use UserFormFieldsTrait;

    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users with 3-step process:
    | Step 1: Full name and email
    | Step 2: Email verification (via link in email)
    | Step 3: Username, password, university, and faculty
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/panel';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showRegistrationForm(Request $request)
    {
        $seoSettings = getSeoMetas('register');
        $pageTitle = !empty($seoSettings['title']) ? $seoSettings['title'] : trans('site.register_page_title');
        $pageDescription = !empty($seoSettings['description']) ? $seoSettings['description'] : trans('site.register_page_title');
        $pageRobot = getPageRobot('register');

        $data = [
            'pageTitle' => $pageTitle,
            'pageDescription' => $pageDescription,
            'pageRobot' => $pageRobot,
        ];

        $authTemplate = getThemeAuthenticationPagesStyleName();
        return view("design_1.web.auth.{$authTemplate}.register.step1", $data);
    }

    public function showStep(Request $request, $step)
    {
        // Handle GET requests for specific steps
        if ($step == 3) {
            // Step 3 requires verification token from session
            $verificationToken = session('registration_step_3_token');
            $verified = session('registration_verified');
            $userId = session('registration_user_id');

            if (!$verificationToken || !$verified || !$userId) {
                return redirect('/register/step/1')->withErrors([
                    'email' => trans('auth.please_complete_verification_first')
                ]);
            }

            $seoSettings = getSeoMetas('register');
            $pageTitle = !empty($seoSettings['title']) ? $seoSettings['title'] : trans('site.register_page_title');
            $pageDescription = !empty($seoSettings['description']) ? $seoSettings['description'] : trans('site.register_page_title');
            $pageRobot = getPageRobot('register');

            $referralSettings = getReferralSettings();
            // Preload universities with their faculties to avoid AJAX calls
            $universities = University::query()->with('faculties:id,name,university_id')->orderBy('name')->get();
            $referralCode = Cookie::get('referral_code');

            // Build faculties map grouped by university_id for JavaScript
            $facultiesByUniversity = [];
            foreach ($universities as $university) {
                $facultiesByUniversity[$university->id] = $university->faculties->map(function($faculty) {
                    return [
                        'id' => $faculty->id,
                        'name' => $faculty->name,
                    ];
                })->sortBy('name')->values()->toArray();
            }

            $data = [
                'pageTitle' => $pageTitle,
                'pageDescription' => $pageDescription,
                'pageRobot' => $pageRobot,
                'verificationToken' => $verificationToken,
                'verified' => $verified,
                'universities' => $universities,
                'faculties' => collect(),
                'facultiesByUniversity' => $facultiesByUniversity,
                'referralSettings' => $referralSettings,
                'referralCode' => $referralCode,
            ];

            $authTemplate = getThemeAuthenticationPagesStyleName();
            return view("design_1.web.auth.{$authTemplate}.register.step3", $data);
        }

        // Other steps redirect to main registration
        return redirect('/register');
    }

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

        // Use different validation for web vs API
        if ($request->wantsJson()) {
            validateParam($data, $rules);
        } else {
            $request->validate($rules);
        }

        // Check if user already exists
        $userCase = User::where('email', $data['email'])
            ->first();

        if ($userCase) {
            if ($userCase->username && $userCase->status == User::$active) {
                if ($request->wantsJson()) {
                    return apiResponse2(0, 'already_registered', trans('api.auth.already_registered'));
                }
                return back()->withErrors(['email' => trans('api.auth.already_registered')])->withInput();
            } else {
                // User exists but incomplete - generate new token for step 3
                $tokenData = [
                    'user_id' => $userCase->id,
                    'email' => $userCase->email,
                    'step' => 3,
                ];
                
                $verificationToken = RegistrationVerificationToken::generateToken($tokenData, 60); // 60 minutes
                
                if ($request->wantsJson()) {
                    return apiResponse2(1, 'go_step_3', trans('api.auth.go_step_3'), [
                        'verification_token' => $verificationToken,
                        'expires_at' => now()->addMinutes(60)->toIso8601String(),
                    ]);
                }
                
                // For web: redirect to step 3 with token
                $referralSettings = getReferralSettings();
                // Preload universities with their faculties to avoid AJAX calls
                $universities = University::query()->with('faculties:id,name,university_id')->orderBy('name')->get();
                $referralCode = Cookie::get('referral_code');

                // Build faculties map grouped by university_id for JavaScript
                $facultiesByUniversity = [];
                foreach ($universities as $university) {
                    $facultiesByUniversity[$university->id] = $university->faculties->map(function($faculty) {
                        return [
                            'id' => $faculty->id,
                            'name' => $faculty->name,
                        ];
                    })->sortBy('name')->values()->toArray();
                }

                $seoSettings = getSeoMetas('register');
                $pageTitle = !empty($seoSettings['title']) ? $seoSettings['title'] : trans('site.register_page_title');
                $pageDescription = !empty($seoSettings['description']) ? $seoSettings['description'] : trans('site.register_page_title');
                $pageRobot = getPageRobot('register');

                $authTemplate = getThemeAuthenticationPagesStyleName();
                return view("design_1.web.auth.{$authTemplate}.register.step3", [
                    'pageTitle' => $pageTitle,
                    'pageDescription' => $pageDescription,
                    'pageRobot' => $pageRobot,
                    'verificationToken' => $verificationToken,
                    'verified' => false,
                    'universities' => $universities,
                    'faculties' => collect(),
                    'facultiesByUniversity' => $facultiesByUniversity,
                    'referralSettings' => $referralSettings,
                    'referralCode' => $referralCode,
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

        // Generate verification code for step 2 (email verification)
        $tokenData = [
            'user_id' => $user->id,
            'email' => $user->email,
            'step' => 2,
        ];
        
        $expiresAt = now()->addMinutes(60);
        $verificationCode = RegistrationVerificationToken::generateVerificationCode($tokenData, 60); // 60 minutes

        // Send verification email with code
        $user->notify(new \App\Notifications\VerifyRegistrationEmailCode($verificationCode, $expiresAt));

        // Return view for web requests, JSON for API
        if ($request->wantsJson()) {
            return apiResponse2(1, 'verification_sent', trans('api.auth.verification_sent'), [
                'message' => 'Please check your email for a 6-digit verification code.',
                'expires_at' => $expiresAt->toIso8601String(),
            ]);
        }

        // Web request - show step 2 view
        $seoSettings = getSeoMetas('register');
        $pageTitle = !empty($seoSettings['title']) ? $seoSettings['title'] : trans('site.register_page_title');
        $pageDescription = !empty($seoSettings['description']) ? $seoSettings['description'] : trans('site.register_page_title');
        $pageRobot = getPageRobot('register');

        $data = [
            'pageTitle' => $pageTitle,
            'pageDescription' => $pageDescription,
            'pageRobot' => $pageRobot,
            'email' => $user->email,
        ];

        $authTemplate = getThemeAuthenticationPagesStyleName();
        return view("design_1.web.auth.{$authTemplate}.register.step2", $data);
    }

    private function stepTwo(Request $request)
    {
        $data = $request->all();
        
        // Step 2: Verify email with verification code
        $rules = [
            'email' => 'required|email|exists:users,email',
            'verification_code' => 'required|string|size:6',
        ];
        
        // Use different validation for web vs API
        if ($request->wantsJson()) {
            validateParam($data, $rules);
        } else {
            $request->validate($rules);
        }

        // Verify the code
        $tokenData = RegistrationVerificationToken::verifyCode($data['email'], $data['verification_code'], 2);
        
        if (!$tokenData) {
            if ($request->wantsJson()) {
                return apiResponse2(0, 'invalid_code', 'Verification code is invalid or expired. Please request a new code.');
            }
            return back()->withErrors(['verification_code' => trans('auth.invalid_verification_code')])->withInput();
        }

        // Find user
        $user = User::where('email', $data['email'])->first();
        
        if (!$user) {
            if ($request->wantsJson()) {
                return apiResponse2(0, 'user_not_found', 'User not found');
            }
            return back()->withErrors(['verification_code' => 'User not found'])->withInput();
        }

        // Mark code as used
        RegistrationVerificationToken::markCodeAsUsed($data['verification_code'], $data['email']);

        // Mark email as verified
        if (empty($user->email_verified_at)) {
            $user->update([
                'email_verified_at' => time(),
            ]);
        }

        // Generate new token for step 3 (profile completion)
        $newTokenData = [
            'user_id' => $user->id,
            'email' => $user->email,
            'step' => 3,
        ];
        
        $verificationToken = RegistrationVerificationToken::generateToken($newTokenData, 60); // 60 minutes

        if ($request->wantsJson()) {
            return apiResponse2(1, 'email_verified', trans('api.auth.email_verified'), [
                'verification_token' => $verificationToken,
                'expires_at' => now()->addMinutes(60)->toIso8601String(),
                'message' => 'Email verified successfully. Please complete your profile.'
            ]);
        }
        
        // Web request: Store verification token and user info in session
        session([
            'registration_step_3_token' => $verificationToken,
            'registration_verified' => true,
            'registration_user_id' => $user->id,
        ]);

        // Redirect to step 3
        return redirect('/register/step/3')->with('success', trans('auth.email_verified_successfully'));
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
        
        // Use different validation for web vs API
        if ($request->wantsJson()) {
            validateParam($data, $rules);
        } else {
            $request->validate($rules);
        }

        // Verify the token
        $tokenData = RegistrationVerificationToken::verifyToken($data['verification_token']);
        
        if (!$tokenData) {
            if ($request->wantsJson()) {
                return apiResponse2(0, 'invalid_token', 'Verification token is invalid or expired');
            }
            return back()->withErrors(['verification_token' => 'Verification token is invalid or expired'])->withInput();
        }

        if ($tokenData['step'] !== 3) {
            if ($request->wantsJson()) {
                return apiResponse2(0, 'invalid_step', 'This token is not valid for step 3');
            }
            return back()->withErrors(['verification_token' => 'This token is not valid for step 3'])->withInput();
        }

        // Find user by user_id or email from token
        $user = null;
        if (!empty($tokenData['user_id'])) {
            $user = User::find($tokenData['user_id']);
        } elseif (!empty($tokenData['email'])) {
            $user = User::where('email', $tokenData['email'])->first();
        }

        if (!$user) {
            if ($request->wantsJson()) {
                return apiResponse2(0, 'user_not_found', 'User not found. Please complete step 1 first.');
            }
            return back()->withErrors(['verification_token' => 'User not found. Please complete step 1 first.'])->withInput();
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

        // Handle referral code
        $referralCode = $data['referral_code'] ?? null;
        if (!empty($referralCode)) {
            Affiliate::storeReferral($user, $referralCode);
        }

        // Handle registration bonus
        $enableRegistrationBonus = false;
        $registrationBonusAmount = null;
        $registrationBonusSettings = getRegistrationBonusSettings();
        if (!empty($registrationBonusSettings['status']) and !empty($registrationBonusSettings['registration_bonus_amount'])) {
            $enableRegistrationBonus = true;
            $registrationBonusAmount = $registrationBonusSettings['registration_bonus_amount'];
            
            $user->update([
                'enable_registration_bonus' => $enableRegistrationBonus,
                'registration_bonus_amount' => $registrationBonusAmount,
            ]);
        }

        // Calculate registration reward
        $registerReward = RewardAccounting::calculateScore(Reward::REGISTER);
        RewardAccounting::makeRewardAccounting($user->id, $registerReward, Reward::REGISTER, $user->id, true);

        // Store registration bonus
        $registrationBonusAccounting = new RegistrationBonusAccounting();
        $registrationBonusAccounting->storeRegistrationBonusInstantly($user);

        // Trigger registered event
        event(new Registered($user));

        // Send notification
        $notifyOptions = [
            '[u.name]' => $user->full_name,
            '[u.role]' => trans("update.role_{$user->role_name}"),
            '[time.date]' => dateTimeFormat($user->created_at, 'j M Y H:i'),
        ];
        sendNotification("new_registration", $notifyOptions, 1);

        // Generate JWT token for API authentication
        $token = auth('api')->attempt([
            'email' => $user->email,
            'password' => $data['password']
        ]);

        // Return JSON for API requests
        if ($request->wantsJson()) {
            return apiResponse2(1, 'registered', trans('api.auth.registered'), [
                'user' => $user,
                'token' => $token,
            ]);
        }

        // Web request - login user and show welcome page
        $this->guard()->login($user);

        $seoSettings = getSeoMetas('register');
        $pageTitle = trans('auth.welcome_to_el_zatuna');
        $pageDescription = trans('auth.account_created_successfully');
        $pageRobot = getPageRobot('register');

        $data = [
            'pageTitle' => $pageTitle,
            'pageDescription' => $pageDescription,
            'pageRobot' => $pageRobot,
            'user' => $user,
        ];

        $authTemplate = getThemeAuthenticationPagesStyleName();
        return view("design_1.web.auth.{$authTemplate}.register.welcome", $data);
    }

    /**
     * DEPRECATED - Old single-step registration (kept for backward compatibility)
     * Use stepRegister() for new 3-step registration flow
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $registerMethod = getGeneralSettings('register_method') ?? 'mobile';

        if (!empty($data['mobile']) and !empty($data['country_code'])) {
            $data['mobile'] = ltrim($data['country_code'], '+') . ltrim($data['mobile'], '0');
        }

        $rules = [
            'country_code' => ($registerMethod == 'mobile') ? 'required' : 'nullable',
            'mobile' => (($registerMethod == 'mobile') ? 'required' : 'nullable') . '|numeric|unique:users',
            'email' => (($registerMethod == 'email') ? 'required' : 'nullable') . '|email|max:255|unique:users',
            'term' => 'required',
            'full_name' => 'required|string|min:3',
            'password' => ['required', 'string', 'confirmed', new \App\Rules\StrongPassword($data['full_name'] ?? null)],
            'password_confirmation' => 'required|same:password',
            'referral_code' => 'nullable|exists:affiliates_codes,code',
            'university_id' => 'required|exists:universities,id',
            'faculty_id' => [
                'required',
                Rule::exists('faculties', 'id')->where(function ($query) use ($data) {
                    $query->where('university_id', $data['university_id'] ?? null);
                })
            ],
        ];

        if (!empty(getGeneralSecuritySettings('captcha_for_register'))) {
            $rules['captcha'] = 'required|captcha';
        }

        return Validator::make($data, $rules, [], [
            'mobile' => trans('auth.mobile'),
            'email' => trans('auth.email'),
            'term' => trans('update.terms'),
            'full_name' => trans('auth.full_name'),
            'password' => trans('auth.password'),
            'password_confirmation' => trans('auth.password_repeat'),
            'referral_code' => trans('financial.referral_code'),
            'university_id' => trans('update.university'),
            'faculty_id' => trans('update.faculty'),
        ]);
    }

    /**
     * DEPRECATED - Old single-step user creation (kept for backward compatibility)
     * Use stepRegister() for new 3-step registration flow
     *
     * @param array $data
     * @return
     */
    protected function create(array $data)
    {
        if (!empty($data['mobile']) and !empty($data['country_code'])) {
            $data['mobile'] = ltrim($data['country_code'], '+') . ltrim($data['mobile'], '0');
        }

        $referralSettings = getReferralSettings();
        $usersAffiliateStatus = (!empty($referralSettings) and !empty($referralSettings['users_affiliate_status']));

        if (empty($data['timezone'])) {
            $data['timezone'] = getGeneralSettings('default_time_zone') ?? null;
        }

        $disableViewContentAfterUserRegister = getFeaturesSettings('disable_view_content_after_user_register');
        $accessContent = !((!empty($disableViewContentAfterUserRegister) and $disableViewContentAfterUserRegister));

        $roleName = Role::$user;
        $roleId = Role::getUserRoleId();

        if (!empty($data['account_type'])) {
            if ($data['account_type'] == Role::$teacher) {
                $roleName = Role::$teacher;
                $roleId = Role::getTeacherRoleId();
            } else if ($data['account_type'] == Role::$organization) {
                $roleName = Role::$organization;
                $roleId = Role::getOrganizationRoleId();
            }
        }

        $user = User::create([
            'role_name' => $roleName,
            'role_id' => $roleId,
            'university_id' => $data['university_id'] ?? null,
            'faculty_id' => $data['faculty_id'] ?? null,
            'mobile' => $data['mobile'] ?? null,
            'email' => $data['email'] ?? null,
            'full_name' => $data['full_name'],
            'status' => User::$pending,
            'access_content' => $accessContent,
            'password' => Hash::make($data['password']),
            'affiliate' => $usersAffiliateStatus,
            'timezone' => $data['timezone'] ?? null,
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

        $this->storeFormFields($data, $user);

        return $user;
    }


    /**
     * DEPRECATED - Old single-step registration handler (kept for backward compatibility)
     * Use stepRegister() for new 3-step registration flow
     */
    public function register(Request $request)
    {
        $accountType = $request->get('account_type', 'user');

        $validate = $this->validator($request->all());

        if ($validate->fails()) {
            $errors = $validate->errors();

            $form = $this->getFormFieldsByType($accountType);

            if (!empty($form)) {
                $fieldErrors = $this->checkFormRequiredFields($request, $form);

                if (!empty($fieldErrors) and count($fieldErrors)) {
                    foreach ($fieldErrors as $id => $error) {
                        $errors->add($id, $error);
                    }
                }
            }

            throw new ValidationException($validate);
        } else {
            $form = $this->getFormFieldsByType($accountType);
            $errors = [];

            if (!empty($form)) {
                $fieldErrors = $this->checkFormRequiredFields($request, $form);

                if (!empty($fieldErrors) and count($fieldErrors)) {
                    foreach ($fieldErrors as $id => $error) {
                        $errors[$id] = $error;
                    }
                }
            }

            if (count($errors)) {
                return back()->withErrors($errors)->withInput($request->all());
            }
        }


        $data = $request->all();

        if (!empty($data['mobile']) and !empty($data['country_code'])) {
            $data['mobile'] = ltrim($data['country_code'], '+') . ltrim($data['mobile'], '0');
        }


        if (!empty($data['mobile'])) {
            $checkIsValid = checkMobileNumber($data['mobile']);

            if (!$checkIsValid) {
                $errors['mobile'] = [trans('update.mobile_number_is_not_valid')];
                return back()->withErrors($errors)->withInput($request->all());
            }
        }

        $user = $this->create($request->all());

        event(new Registered($user));

        $notifyOptions = [
            '[u.name]' => $user->full_name,
            '[u.role]' => trans("update.role_{$user->role_name}"),
            '[time.date]' => dateTimeFormat($user->created_at, 'j M Y H:i'),
        ];
        sendNotification("new_registration", $notifyOptions, 1);

        $registerMethod = getGeneralSettings('register_method') ?? 'mobile';

        $value = $request->get($registerMethod);
        if ($registerMethod == 'mobile') {
            $value = $data['mobile']; // Country code applied in the above lines
        }

        $referralCode = $request->get('referral_code', null);
        if (!empty($referralCode)) {
            session()->put('referralCode', $referralCode);
        }

        // Phone verification commented out
        // $verificationController = new VerificationController();
        // $checkConfirmed = $verificationController->checkConfirmed($user, $registerMethod, $value);

        // $referralCode = $request->get('referral_code', null);

        // if ($checkConfirmed['status'] == 'send') {
        //     if (!empty($referralCode)) {
        //         session()->put('referralCode', $referralCode);
        //     }
        //     return redirect('/verification');
        // } elseif ($checkConfirmed['status'] == 'verified') {

        // Skip verification and activate user immediately
            $this->guard()->login($user);

            $enableRegistrationBonus = false;
            $registrationBonusAmount = null;
            $registrationBonusSettings = getRegistrationBonusSettings();
            if (!empty($registrationBonusSettings['status']) and !empty($registrationBonusSettings['registration_bonus_amount'])) {
                $enableRegistrationBonus = true;
                $registrationBonusAmount = $registrationBonusSettings['registration_bonus_amount'];
            }


            $user->update([
                'status' => User::$active,
                'enable_registration_bonus' => $enableRegistrationBonus,
                'registration_bonus_amount' => $registrationBonusAmount,
            ]);

            $registerReward = RewardAccounting::calculateScore(Reward::REGISTER);
            RewardAccounting::makeRewardAccounting($user->id, $registerReward, Reward::REGISTER, $user->id, true);

            if (!empty($referralCode)) {
                Affiliate::storeReferral($user, $referralCode);
            }

            $registrationBonusAccounting = new RegistrationBonusAccounting();
            $registrationBonusAccounting->storeRegistrationBonusInstantly($user);

            if ($response = $this->registered($request, $user)) {
                return $response;
            }

            return $request->wantsJson()
                ? new JsonResponse([], 201)
                : redirect($this->redirectPath());
        // }
    }

}
