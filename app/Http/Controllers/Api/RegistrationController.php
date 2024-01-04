<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ValidationRules;
use App\Http\Requests\Validate;
use App\Http\Resources\ErrorResource;
use App\Http\Requests\ValidationMessages;
use App\Models\Driver;
use App\Models\User;
use App\Services\DeviceService;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;
use App\Helpers\Curl;
use stdClass;


class RegistrationController extends Controller
{
    use curl;
    protected $rules;
    protected $validationMessages;
    protected $DeviceService;

    public function __construct(ValidationRules $rules, ValidationMessages $validationMessages, DeviceService $DeviceService)
    {
        $this->rules = $rules;
        $this->validationMessages = $validationMessages;
        $this->DeviceService = $DeviceService;
    }

    public function noTokenFound()
    {
        $response = new stdClass();
        $response->status_code = "401";
        $response->message = "Unauthorized";
        $response->error = "user access token is missing or expired";
        $response->data = "";
        return response()->json($response, $response->status_code);
    }

    public function apiJsonResponse($code, $message, $data, $error)
    {
        $response = new stdClass();
        $response->status_code = $code;
        $response->message = $message;
        $response->error = $error;
        $response->data = $data;
        return response()->json($response, $response->status_code);
    }


    public function adminLogin()
    {
        $adminEmail =  Config::get('constants.Constants.adminEmail');
        $adminPassword = Config::get('constants.Constants.adminPassword');
        $data = 'email=' . $adminEmail . '&password=' . $adminPassword;
        $response = static::curl('/api/session', 'POST', '', $data, array(Config::get('constants.Constants.urlEncoded')));
        $res = json_decode($response->response);
        // dd($response);
    }

    public function login(Request $request, Validate $validate)
    {
        $validationErrors = $validate->validate($request, $this->rules->driverLoginAuthenticationValidationRules(), $this->validationMessages->driverLoginAuthenticationValidationMessages());
        if ($validationErrors) {
            return (new ErrorResource($validationErrors))->response()->setStatusCode(400);
        }
        try {
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                $user = User::where(['email' => $request->email])->first();
                $driver = Driver::where('user_id', $user->id)->first();
                $data = new stdClass();
                // dd($driver);
                $data->bearer_token = $user->createToken('DriverLoginAuth')->accessToken;
                $data->unique_id = $driver->unique_id;
                return $this->apiJsonResponse(200, "Login Success", $data, "");
            } else {
                return $this->apiJsonResponse(400, "Invalid Login", '', "Driver Credentials do not match");
            }
        } catch (\Throwable $e) {
            return $this->apiJsonResponse(400, "Something went wrong", '', $e->getMessage());
        }
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return $this->apiJsonResponse(200, "Success", '', "Logout Successfully");
    }

    public function profile(Request $request)
    {
        try {
            $driver = Driver::where('user_id', $request->user()->id)->first();
            $data = new stdClass();
            $data->name = $driver->name;
            $data->email = $request->user()->email;
            $data->phone = $driver->phone;
            $data->license_no = $driver->license_no;
            $data->avatar = $driver->avatar;
            return $this->apiJsonResponse(200, "Success", $data, "");
        } catch (\Throwable $e) {
            return $this->apiJsonResponse(400, "Something went wrong", '', $e->getMessage());
        }
    }

    public function updateProfile(Request $request, Validate $validate)
    {
        $validationErrors = $validate->validate($request, $this->rules->driverProfileUpdateValidationRules(), $this->validationMessages->driverProfileUpdateValidationMessages());
        if ($validationErrors) {
            return (new ErrorResource($validationErrors))->response()->setStatusCode(400);
        }
        try {
            $driver = User::where('id', '!=', $request->user()->id)->where('email', $request->email)->first();
            if ($driver != null) {
                return $this->apiJsonResponse(400, "Invalid data", '', 'Email Already exists');
            }
            $user = User::find($request->user()->id);
            $user->email = $request->email;
            $user->save();
            $driver = Driver::where('user_id', $request->user()->id)->first();
            $driver->name = $request->name;
            $driver->phone = $request->phone;
            $driver->license_no = $request->license_no;
            if ($request->has('avatar')) {
                $base64image = preg_replace('#^data:image/[^;]+;base64,#', '', $request->input('avatar'));

                if ($imageData = base64_decode($base64image)) {
                    $image = Image::make($imageData);
                    $side = max($image->width(), $image->height());

                    $background = Image::canvas($side, $side, '#ffffff')->insert($image, 'center');

                    $filename = uniqid() . '.jpg';
                    $directory = public_path('storage/users');

                    if (!File::isDirectory($directory)) {
                        File::makeDirectory($directory, 0755, true, true);
                    }

                    $background->save($directory . '/' . $filename);

                    $driver->avatar = asset('/storage/users') . '/' . $filename;
                }
            }

            $driver->save();
            return $this->apiJsonResponse(200, "Profile updated", '', "");
        } catch (\Throwable $e) {
            return $this->apiJsonResponse(400, "Something went wrong", '', $e->getMessage());
        }
    }

    public function changePassword(Request $request, Validate $validate)
    {
        $validationErrors = $validate->validate($request, $this->rules->changePasswordValidationRules(), $this->validationMessages->changePasswordValidationMessages());
        if ($validationErrors) {
            return (new ErrorResource($validationErrors))->response()->setStatusCode(400);
        }
        try {
            $user = User::find($request->user()->id);
            if (!Hash::check($request->old_password, $user->password)) {
                return $this->apiJsonResponse(401, "Invalid Request", '', "Old password is not correct");
            }
            $user->password = Hash::make($request->new_password);
            $user->save();
            return $this->apiJsonResponse(200, "Password changed", '', "");
        } catch (\Throwable $e) {
            return $this->apiJsonResponse(400, "Something went wrong", '', $e->getMessage());
        }
    }
}
