<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;
use App\Helpers\Curl;
use App\Models\Employee;
use Session;
class RegistrationController extends Controller
{
    use curl;
    public function index()
    {
        return view('login');
    }


    public function login(Request $request)
    {
        $user = User::where('email', $request->username)->first();
        if ($user == null) {
            return response()->json(['result' => 'invalid']);
        }
        if (Auth::attempt(['email' => $request->username, 'password' => $request->password])) {
            Session::put('token',0);
            if ($user->type == 'superadmin') {
                $this->adminLogin();
                session(['allowed_by_google' => 3 ]);
                session(['name' => 'Admin' ]);
                return response()->json(['result' => 'superadmin']);
            } else if($user->type == 'employee') {
                $this->adminLogin();
                $name = Employee::where('user_id',$user->id)->pluck('name');
                session(['name' => $name[0] ]);
                return response()->json(['result' => 'employee']);
            } else {
                return response()->json(['result' => 'invalid']);
            }
        } else {
            return response()->json(['result' => 'invalid']);
        }
    }
    public function saveToken(Request $request){
       
        $admin_data=User::where('id','=',$request->admin_id)->first();
        Session::put('token',1);
        if($admin_data){  
            if($admin_data->fcm_token!=$request->token || $admin_data->fcm_token==null){
            
                $admin_data->fcm_token=$request->token;
                $admin_data->save();
                return response()->json('token saved');
            }
        }
        
        return response()->json('token already saved');

    }

    public function profile()
    {
        return view('profile');
    }

    public function updatePassword(Request $request)
    {
        // dd($request->all());
        $user = Auth::user();
        $validatedData = $request->validate([
            'old_password' => [
                'required',
                function ($attribute, $value, $fail) use ($user) {
                    if (!Hash::check($value, $user->password)) {
                        $fail('The old password does not match.');
                    }
                },
            ],
        ]);


        $password = Hash::make($request->new_password);
        $user->update(['password' => $password]);
        return redirect('/profile/personal')->with('success', 'Password Updated Successfully!');
    }
    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }


    public function adminLogin(){
        $adminEmail =  Config::get('constants.Constants.adminEmail');
        $adminPassword = Config::get('constants.Constants.adminPassword');
        $data = 'email=' . $adminEmail . '&password=' . $adminPassword;
        $response = static::curl('/api/session', 'POST', '', $data, array(Config::get('constants.Constants.urlEncoded')));
        $res = json_decode($response->response);
        // dd($response);
    }
}
