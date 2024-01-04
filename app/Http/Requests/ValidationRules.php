<?php

namespace App\Http\Requests;


class ValidationRules
{
    public function driverSignupAuthenticationValidationRules(): array
    {
        return [
            'name' => 'required',
            'phone' => 'required',
            'email' => 'required|unique:users,email',
            'license_no' => 'required',
            // 'license_expiry' => 'required',
            'password' => 'required',
            'avatar' => 'required',
        ];
    }
    
    public function driverLoginAuthenticationValidationRules(): array
    {
        return [
            'email' => 'required|exists:users,email,type,driver',
            'password' => 'required',
        ];
    }
    
    
    public function driverProfileUpdateValidationRules(): array
    {
        return [
            'name' => 'required',
            'phone' => 'required',
            'email' => 'required',
            // 'license_no'` => 'required',
            // 'license_ex`piry' => 'required',
        ];
    }
    
    public function changePasswordValidationRules(): array
    {
        return [
            'old_password' => 'required',
            'new_password' => 'required',
        ];
    }
}
