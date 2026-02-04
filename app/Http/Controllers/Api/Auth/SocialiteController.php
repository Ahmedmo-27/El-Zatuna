<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\Controller;
use App\Models\Affiliate;
use App\Models\Role;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Exception;
use App\User;

class SocialiteController extends Controller
{

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function handleGoogleCallback(Request $request)
    {
        try {
            // Validate OAuth data
            validateParam($request->all(), [
                'email' => 'required|email',
                'name' => 'required',
                'id' => 'required'
            ]);
            
            $data = $request->all();
            
            // Validate OAuth token/id is not empty
            if (empty($data['id']) || strlen($data['id']) < 5) {
                return apiResponse2(0, 'invalid_oauth_token', trans('api.auth.invalid_oauth_token'), [
                    'provider' => 'google',
                    'error_detail' => 'The provided OAuth token is invalid or expired'
                ]);
            }
            
            // Check if email already exists with different provider
            $existingUser = User::where('email', $data['email'])
                ->where(function($query) use ($data) {
                    $query->whereNull('google_id')
                          ->orWhere('google_id', '!=', $data['id']);
                })
                ->first();
            
            if ($existingUser && empty($existingUser->google_id)) {
                // Email registered via email/password or other provider
                $registeredVia = 'email';
                if (!empty($existingUser->facebook_id)) {
                    $registeredVia = 'facebook';
                }
                
                return apiResponse2(0, 'email_already_registered', trans('api.auth.email_already_registered'), [
                    'email' => $data['email'],
                    'registered_via' => $registeredVia,
                    'suggestion' => $registeredVia === 'email' 
                        ? 'Please login with your email and password' 
                        : 'Please login with ' . $registeredVia
                ]);
            }
            
            $user = User::where('google_id', $data['id'])
                ->orWhere('email', $data['email'])
                ->first();
                
            $registered = true;
            
            if (empty($user)) {
                $registered = false;
                $user = User::create([
                    'full_name' => $data['name'],
                    'email' => $data['email'],
                    'google_id' => $data['id'],
                    'role_id' => Role::getUserRoleId(),
                    'role_name' => Role::$user,
                    'status' => User::$active,
                    'verified' => true,
                    'created_at' => time(),
                    'password' => null
                ]);
            }
            
            $user->update([
                'google_id' => $data['id'],
            ]);

            $responseData = [];
            $responseData['user_id'] = $user->id;
            $responseData['already_registered'] = $registered;
            
            if ($registered) {
                // Check device limit before allowing login
                $checkLoginDeviceLimit = $this->checkLoginDeviceLimit($user);
                if ($checkLoginDeviceLimit != "ok") {
                    return apiResponse2(0, 'limit_account', trans('auth.limit_account'));
                }

                $token = auth('api')->tokenById($user->id);
                $responseData['token'] = $token;
                
                // Increment logged_count after successful login
                $user->update([
                    'logged_count' => $user->logged_count + 1
                ]);
                
                return apiResponse2(1, 'login', trans('api.auth.login'), $responseData);
            }
            
            return apiResponse2(1, 'registered', trans('api.auth.registered'), $responseData);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return apiResponse2(0, 'validation_error', trans('api.auth.validation_error'), [
                'errors' => $e->errors()
            ]);
        } catch (\Exception $e) {
            return apiResponse2(0, 'oauth_provider_error', trans('api.auth.oauth_error'), [
                'provider' => 'google',
                'error_detail' => 'Unable to verify credentials with Google',
                'message' => config('app.debug') ? $e->getMessage() : 'Authentication failed'
            ]);
        }
    }

    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->stateless()->redirect();
    }

    public function handleFacebookCallback(Request $request)
    {
        try {
            // Validate OAuth data
            validateParam($request->all(), [
                'email' => 'required|email',
                'name' => 'required',
                'id' => 'required'
            ]);
            
            $data = $request->all();
            
            // Validate OAuth token/id is not empty
            if (empty($data['id']) || strlen($data['id']) < 5) {
                return apiResponse2(0, 'invalid_oauth_token', trans('api.auth.invalid_oauth_token'), [
                    'provider' => 'facebook',
                    'error_detail' => 'The provided OAuth token is invalid or expired'
                ]);
            }
            
            // Check if email already exists with different provider
            $existingUser = User::where('email', $data['email'])
                ->where(function($query) use ($data) {
                    $query->whereNull('facebook_id')
                          ->orWhere('facebook_id', '!=', $data['id']);
                })
                ->first();
            
            if ($existingUser && empty($existingUser->facebook_id)) {
                // Email registered via email/password or other provider
                $registeredVia = 'email';
                if (!empty($existingUser->google_id)) {
                    $registeredVia = 'google';
                }
                
                return apiResponse2(0, 'email_already_registered', trans('api.auth.email_already_registered'), [
                    'email' => $data['email'],
                    'registered_via' => $registeredVia,
                    'suggestion' => $registeredVia === 'email' 
                        ? 'Please login with your email and password' 
                        : 'Please login with ' . $registeredVia
                ]);
            }
            
            $user = User::where('facebook_id', $data['id'])
                ->orWhere('email', $data['email'])
                ->first();
                
            $registered = true;
            
            if (empty($user)) {
                $registered = false;
                $user = User::create([
                    'full_name' => $data['name'],
                    'email' => $data['email'],
                    'facebook_id' => $data['id'],
                    'role_id' => Role::getUserRoleId(),
                    'role_name' => Role::$user,
                    'status' => User::$active,
                    'verified' => true,
                    'created_at' => time(),
                    'password' => null
                ]);
            }
            
            $responseData = [];
            $responseData['user_id'] = $user->id;
            $responseData['already_registered'] = $registered;
            
            if ($registered) {
                // Check device limit before allowing login
                $checkLoginDeviceLimit = $this->checkLoginDeviceLimit($user);
                if ($checkLoginDeviceLimit != "ok") {
                    return apiResponse2(0, 'limit_account', trans('auth.limit_account'));
                }

                $token = auth('api')->tokenById($user->id);
                $responseData['token'] = $token;
                
                // Increment logged_count after successful login
                $user->update([
                    'logged_count' => $user->logged_count + 1
                ]);
                
                return apiResponse2(1, 'login', trans('api.auth.login'), $responseData);
            }
            
            return apiResponse2(1, 'registered', trans('api.auth.registered'), $responseData);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return apiResponse2(0, 'validation_error', trans('api.auth.validation_error'), [
                'errors' => $e->errors()
            ]);
        } catch (\Exception $e) {
            return apiResponse2(0, 'oauth_provider_error', trans('api.auth.oauth_error'), [
                'provider' => 'facebook',
                'error_detail' => 'Unable to verify credentials with Facebook',
                'message' => config('app.debug') ? $e->getMessage() : 'Authentication failed'
            ]);
        }
    }

    private function checkLoginDeviceLimit($user)
    {
        // Check if user has a custom allowed_devices value
        $limitCount = !empty($user->allowed_devices) ? $user->allowed_devices : 1;

        // Fallback to global security settings if needed
        $securitySettings = getGeneralSecuritySettings();
        if (!empty($securitySettings) and !empty($securitySettings['login_device_limit'])) {
            // If user's allowed_devices is not set or is 1 (default), use global setting
            if (empty($user->allowed_devices) || $user->allowed_devices == 1) {
                $limitCount = !empty($securitySettings['number_of_allowed_devices']) ? $securitySettings['number_of_allowed_devices'] : 1;
            }
        }

        $count = $user->logged_count;

        if ($count >= $limitCount) {
            return "no";
        }

        return 'ok';
    }
}
