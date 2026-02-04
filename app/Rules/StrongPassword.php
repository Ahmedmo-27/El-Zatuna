<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class StrongPassword implements Rule
{
    protected $errors = [];
    protected $username = null;

    public function __construct($username = null)
    {
        $this->username = $username;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $this->errors = [];

        // Minimum length check
        if (strlen($value) < 8) {
            $this->errors[] = trans('validation.password_min_length');
        }

        // Uppercase letter check
        if (!preg_match('/[A-Z]/', $value)) {
            $this->errors[] = trans('validation.password_uppercase');
        }

        // Lowercase letter check
        if (!preg_match('/[a-z]/', $value)) {
            $this->errors[] = trans('validation.password_lowercase');
        }

        // Number check
        if (!preg_match('/[0-9]/', $value)) {
            $this->errors[] = trans('validation.password_number');
        }

        // Special character check
        if (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $value)) {
            $this->errors[] = trans('validation.password_special_char');
        }

        // Common passwords check
        if ($this->isCommonPassword($value)) {
            $this->errors[] = trans('validation.password_common');
        }

        // Username check (if provided)
        if ($this->username && strtolower($value) === strtolower($this->username)) {
            $this->errors[] = trans('validation.password_same_as_username');
        }

        return empty($this->errors);
    }

    /**
     * Get the validation error message.
     *
     * @return string|array
     */
    public function message()
    {
        return !empty($this->errors) ? $this->errors : trans('validation.password_requirements');
    }

    /**
     * Check if password is in common passwords list
     *
     * @param string $password
     * @return bool
     */
    protected function isCommonPassword($password)
    {
        $commonPasswords = [
            'password', 'password123', '123456', '12345678', '123456789',
            'qwerty', 'abc123', 'monkey', '1234567', '12345678',
            '123123', '1234567890', 'qwerty123', '000000', '1234567',
            'dragon', 'master', 'monkey', 'letmein', 'login',
            'princess', 'qwertyuiop', 'solo', 'passw0rd', 'starwars',
            'welcome', 'admin', 'administrator', 'Password1', 'Password123',
            'Pass@123', 'P@ssw0rd', 'Welcome123', 'Admin123', 'Test1234'
        ];

        return in_array(strtolower($password), array_map('strtolower', $commonPasswords));
    }
}
