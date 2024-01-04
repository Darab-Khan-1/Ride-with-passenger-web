<?php

namespace App\Http\Requests;


class ValidationRules
{
   
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

    public function startTripValidationRules(): array
    {
        return [
            'trip_id' => 'required|exists:trips,id',
        ];
    }

    public function stopTripValidationRules(): array
    {
        return [
            'stop_id' => 'required|exists:stops,id',
            'trip_id' => 'required|exists:trips,id',
        ];
    }
}
