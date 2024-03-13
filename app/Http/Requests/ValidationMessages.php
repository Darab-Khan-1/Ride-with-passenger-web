<?php

namespace App\Http\Requests;


class ValidationMessages
{


    public function driverLoginAuthenticationValidationMessages(): array
    {
        return ([
            'email.required' => "email is required",
            'email.exists' => "Email not found",
            'password.required' => "password is required",
        ]);
    }

    public function fcmTokenValidationMessages(): array
    {
        return ([
            'fcm.required' => "fcm is required",
        ]);
    }

    public function driverProfileUpdateValidationMessages(): array
    {
        return ([
            'name.required' => "name is required",
            'phone.required' => "phone is required",
            'email.required' => "email is required",
            'license_no.required' => "license no is required",
            'license_expiry.required' => "license expiry is required",
        ]);
    }
    public function changePasswordValidationMessages(): array
    {
        return ([
            'old_password.required' => "old password is required",
            'new_password.required' => "new password is required",
        ]);
    }

    public function startTripValidationMessages(): array
    {
        return ([
            'trip_id.required' => "trip id is required",
            'trip_id.exists' => "trip not found",
        ]);
    }

    public function acceptTripValidationMessages(): array
    {
        return ([
            'trip_id.required' => "trip id is required",
            'trip_id.exists' => "trip not found",
        ]);
    }

    public function stopTripValidationMessages(): array
    {
        return ([
            'stop_id.required' => "stop id is required",
            'stop_id.exists' => "stop not found",
            'trip_id.required' => "trip id is required",
            'trip_id.exists' => "trip not found",
        ]);
    }
}
