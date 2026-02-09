<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication
    |--------------------------------------------------------------------------
    */
    'admin_login_title' => 'Admin Login',
    'failed' => 'Incorrect username, password, or user does not exist.',
    'throttle' => 'Too many login attempts. Please try again in :seconds seconds.',
    'admin_panel' => 'Admin Panel',
    'welcome' => 'Welcome to',
    'admin_tagline' => 'Please log in to manage and control everything.',

    'email' => 'Email',
    'mobile' => 'Phone',
    'country' => 'Country',
    'full_name' => 'Full Name',
    'email_or_mobile' => 'Email or Phone',
    'email_or_mobile_or_name' => 'Email, Phone, or Name',
    'your_email' => 'Your email address',
    'password' => 'Password',
    'retype_password' => 'Retype Password',
    'password_repeat' => 'Retype Password',
    'login' => 'Login',
    'signup' => 'Sign Up',
    'register' => 'Register',
    'remember_me' => 'Remember me',
    'forget_password' => 'Password Recovery',
    'forget_your_password' => 'Forgot your password?',
    'dont_have_account' => 'Don\'t have an account?',
    'hint_password' => 'Please enter your password.',
    'hint_email' => 'Please enter your email.',

    'role_admin' => 'Admin',
    'role_normal' => 'User',

    'login_h1' => 'Log in to your account',
    'or' => 'or',
    'google_login' => 'Login with Google account',
    'facebook_login' => 'Login with Facebook account',

    'i_agree_with' => 'I agree to the',
    'terms_and_rules' => 'terms & rules',
    'already_have_an_account' => 'Already have an account?',

    'reset_password' => 'Reset Password',
    'send_email_for_reset_password' => 'A password recovery link has been sent to your email address. Please check your inbox.',
    'reset_password_success' => 'Your password was successfully changed!',
    'reset_password_token_invalid' => 'Invalid request. Please try again.',

    'name' => 'Name',
    'language' => 'Language',
    'join_newsletter' => 'Subscribe to email newsletter',
    'public_messages' => 'Enable profile messages',
    'images' => 'Images',
    'profile_image' => 'Profile Image',
    'select_image' => 'Select an Image',
    'profile_cover' => 'Profile Cover',

    'education_no_result' => 'No degree has been added.',
    'education_no_result_hint' => 'Your academic qualifications will be shown on your profile.',

    'experience_no_result' => 'No experience has been added.',
    'experience_no_result_hint' => 'Add your professional experience to help users learn more about you.',

    'ban_msg' => 'Your account is banned until :date.',
    'incorrect_login' => 'Incorrect username or password.',

    'fail_login_by_google' => 'Login with Google failed.',
    'fail_login_by_facebook' => 'Login with Facebook failed.',

    'email_confirmation' => 'Email Verification',
    'email_confirmation_template_body' => 'To verify your email address (:email), enter the following code on the :site website.',
    'in' => 'for',

    'verification' => 'Verify',
    'account_verification' => 'Account Verification',
    'account_verification_hint' => 'Please enter the code sent to your :username.',
    'code' => 'Verification Code',
    'resend_code' => 'Resend Code',
    'verify_your_email_address' => 'Verify Your Email Address',
    'click_here' => 'Click here',

    'verification_link_has_been_sent_to_your_email' => 'A new verification link has been sent to your email address.',

    'login_failed_your_account_is_not_verified' => 'Login failed. Your email or phone number has not been verified.',
    'logout' => 'Logout',
    'phone' => 'Phone',
    'reset_password_notification' => 'Reset Password',
    'new_password' => 'New Password',
    'retype_new_password' => 'Retype New Password',
    'reset_your_password' => 'Reset your Password',

    // Token refresh
    'token_refreshed' => 'Token refreshed successfully',
    'token_expired' => 'Token has expired. Please login again.',
    'token_invalid' => 'Invalid token. Please login again.',
    'token_refresh_failed' => 'Failed to refresh token. Please login again.',

    // Session management
    'session_revoked' => 'Session revoked successfully',
    'cannot_delete_current_session' => 'Cannot delete current session',

    // Data export
    'data_export_requested' => 'Data export requested successfully. You will receive a notification when it is ready.',
    'data_export_already_requested' => 'You already have a pending data export request.',
    'data_export_processing' => 'Your data export is still being processed.',
    'data_export_failed' => 'Data export failed. Please try again.',
    'data_export_file_not_found' => 'Export file not found.',
    'data_export_ready' => 'Your data export is ready for download.',
    'data_export_unknown_status' => 'Unknown export status.',

    // Registration verification
    'verification_sent' => 'Verification code sent successfully. Please check your email.',
    'verified' => 'Verification successful. Please proceed to complete your profile.',
    'already_registered' => 'This account is already registered. Please login instead.',
    'go_step_3' => 'Please proceed to step 3 to complete your profile.',
    'not_verified' => 'Your account is not verified. Please check your email for verification code.',
    'inactive_account' => 'Your account is inactive. Please contact support.',
    'limit_account' => 'Device login limit reached. Please logout from another device first.',
    'banned_account' => 'Your account has been banned.',
    'incorrect' => 'Incorrect email or password.',
    'not_login' => 'You are not logged in. Please login first.',
    'device_not_registered_please_login_from_registered_device' => 'This device is not registered. Please login from a registered device or contact support.',
    'device_not_trusted_please_contact_support' => 'This device is not trusted. Please contact support to enable access.',
    'registered_devices_limit_reached' => 'You have reached the maximum number of registered devices (:limit). Please remove a device before adding a new one.',

    // Email Verification
    'email_verification' => 'Email Verification',
    'hello' => 'Hello',
    'email_verification_body' => 'Thank you for registering with :site. Please click the button below to verify your email address.',
    'verify_email_button' => 'Verify Email Address',
    'email_verification_link_expires' => 'This verification link will expire in :minutes minutes.',
    'if_you_did_not_register' => 'If you did not create an account, no further action is required.',
    'regards' => 'Regards',
    'email_verified' => 'Email verified successfully',

    // 3-Step Registration
    'step_1_of_3' => 'Step 1 of 3',
    'step_2_of_3' => 'Step 2 of 3',
    'step_3_of_3' => 'Step 3 of 3',
    'basic_information' => 'Basic Information',
    'email_verification' => 'Email Verification',
    'final_details' => 'Final Details',
    'continue' => 'Continue',
    'verification_email_sent_to' => 'Verification email sent to',
    'didnt_receive_email' => 'Didn\'t receive the email?',
    'resend_email' => 'Resend Email',
    'sending' => 'Sending',
    'email_sent' => 'Email Sent',
    'failed_to_send_email' => 'Failed to send email. Please try again.',
    'check_your_email' => 'Check Your Email',
    'verify_your_email' => 'Verify Your Email',
    'verification_email_sent' => 'Verification Email Sent',
    'we_sent_verification_email_to' => 'We have sent a verification email to',
    'please_check_your_inbox' => 'Please check your inbox and spam folder',
    'click_verification_link_instruction' => 'Click the verification link in the email to continue with your registration.',
    'resend_verification_email' => 'Resend Verification Email',
    'email_sent_successfully' => 'Email Sent Successfully',
    'wrong_email_address' => 'Used the wrong email address?',
    'start_over' => 'Start Over',
    'almost_there' => 'Almost There',
    'complete_your_profile' => 'Complete Your Profile',
    'email_verified_successfully' => 'Email verified successfully!',
    'username' => 'Username',
    'complete_registration' => 'Complete Registration',
    'welcome_to_el_zatuna' => 'Welcome to El Zatuna',
    'congratulations' => 'Congratulations',
    'account_created_successfully' => 'Your account has been created successfully!',
    'youre_all_set' => 'You\'re all set!',
    'redirecting_to_dashboard' => 'You will be redirected to your dashboard in a moment.',
    'your_account_details' => 'Your Account Details',
    'redirecting_in' => 'Redirecting in',
    'seconds' => 'seconds',
    'go_to_dashboard' => 'Go to Dashboard',
    
    // Code-based Email Verification
    'enter_verification_code' => 'Enter Verification Code',
    'we_sent_6_digit_code_to' => 'We sent a 6-digit verification code to',
    'verify_email' => 'Verify Email',
    'code_expires_in_60_minutes' => 'Code expires in 60 minutes',
    'enter_6_digit_code_from_email' => 'Enter the 6-digit code from your email to continue.',
    'didnt_receive_code' => 'Didn\'t receive the code?',
    'resend_verification_code' => 'Resend Verification Code',
    'code_sent_successfully' => 'Code Sent Successfully',
    'failed_to_send_code' => 'Failed to send code. Please try again.',
    'invalid_verification_code' => 'Invalid or expired verification code. Please try again.',
    'please_enter_all_6_digits' => 'Please enter all 6 digits.',
    'verifying' => 'Verifying',
    'verification_code' => 'Verification Code',
    'code_expires_in' => 'Code expires in',
    'one_hour' => '1 hour',
    'security_notice' => 'Security Notice',
    'having_trouble' => 'Having trouble?',
    'contact_support' => 'Contact Support',
    
    // Error messages
    'verification_link_invalid_or_expired' => 'The verification link is invalid or has expired. Please request a new one.',
    'verification_link_invalid_step' => 'This verification link is not valid for this step.',
    'user_not_found' => 'User not found.',
    'please_complete_verification_first' => 'Please complete email verification first.',

];
