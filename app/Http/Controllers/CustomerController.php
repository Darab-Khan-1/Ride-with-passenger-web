<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;

class CustomerController extends Controller
{
    public function customerInfo(Request $request)
    {
        if ($request->ajax()) {
            $customer = Customer::with('user', 'role')->get();
            return DataTables::of($customer)->make(true);
        }
        $total = Customer::count();
        // dd($total);
        // $roles = Role::all();
        $roles =  Role::where('name', 'Customer')->first();
        // dd($roles);
        return view('customer', compact('total', 'roles'));
    }

    public function create(Request $request)
    {
        $validation = User::where('email', $request->email)->first();
        // dd($validation);
        if ($validation) {
            // dd('hi');
            return redirect('customer')->with('error', 'customer already exist');
        }
        // dd($request->email);
        $user = new User();
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->type = 'customer';
        $user->save();

        $role = Role::find($request->role_id);
        // dd($role);
        if ($role) {
            $user->syncRoles([$role]);
        }
        $customer = new Customer();
        $customer->name = $request->name;
        $customer->phone = $request->phone;
        // dd($request->role_id);
        $customer->user_id = $user->id;
        $customer->role_id = $request->role_id;
        $customer->address = $request->address;
        $customer->company_phone = $request->company_phone;
        $customer->company_name = $request->company;
        // dd($request->company);

        if ($request->file('profile_avatar')) {
            $imageName = time() . '.' . rand(10, 10000) . '.' . $request->file('profile_avatar')->getClientOriginalExtension();
            $directory = public_path('storage/users');
            if (!File::isDirectory($directory)) {
                File::makeDirectory($directory, 0755, true, true);
            }
            $imagePath = $directory . "/" . $imageName;
            $image = Image::make($request->file('profile_avatar'));
            $side = max($image->width(), $image->height());
            $background = Image::canvas($side, $side, '#ffffff');
            $background->insert($image, 'center');
            $background->save($imagePath);
            $driver_avatar = asset('/storage/users') . '/' . $imageName;
        } else {
            $driver_avatar = url('assets/media/users/blank.png');
        }
        $customer->avatar = $driver_avatar;
        $customer->save();
        if ($customer->save()) {
            return redirect('customer')->with('success', 'Successfully created');
        } else {
            return redirect('customer')->with('error', 'Not created');
        }
    }

    public function delete($id)
    {
        $customer = Customer::where('user_id', $id)->first();
        $customer->delete();

        $user = User::find($id);
        $user->email = $user->email . "-removed" . $id;
        $user->save();
        $user->delete();
        return redirect('customer')->with('success', 'Customer deleted successfully');
    }

    public function get($id)
    {
        $customer = Customer::where('user_id', $id)->with('user')->first();
        return $customer;
    }

    public function update(Request $request)
    {
        $user = User::where('email', $request->email)->whereNot('id', $request->user_id)->first();
        if ($user) {
            return redirect()->back()->with('error', 'Email already exists');
        }
        $user = User::find($request->user_id);
        $user->email = $request->email;
        // dd($user);
        $user->save();

        $customer = Customer::where('user_id', $request->user_id)->first();
        // dd($customer);
        $customer->name = $request->name;
        $customer->phone = $request->phone;
        $customer->user_id = $user->id;
        // $customer->role_id = $roles->name;
        $customer->address = $request->address;
        if ($request->file('profile_avatar')) {
            $imageName = time() . "." . rand(10, 10000) . "." . $request->file('profile_avatar')->getClientOriginalExtension();
            $directory = public_path('storage/users');
            if (file::isDirectory($directory)) {
                file::makeDirectory($directory, 0755, true, true);
            }
            $imagePath = $directory . "/" . $imageName;
            $image = Image::make($request->file('profile_avatar'));
            $side = max($image->width(), $image->height());
            $background = Image::canvas($side, $side, '#fffff');
            $background->insert($image, 'center');
            $background->save($imagePath);
            $driver_avatar = asset('storage/users') . "/" . $imageName;
        } else {
            $driver_avatar = url('assets/media/users/blank.png');
        }
        $customer->avatar = $driver_avatar;
        $customer->save();
        if ($customer->save()) {
            return redirect('customer')->with('success', 'Successfully updated');
        }
        return redirect('customer')->with('error', 'Some error');
    }

    public function change_password(Request $request)
    {
        // dd($request->user_id);
        // dd($request->all());
        $user = User::find($request->user_id);
        $user->tokens->each(function ($token, $key) {
            $token->delete();
        });
        $user->password = Hash::make($request->password);
        $user->save();
        return redirect()->back()->with('success', 'Password changed');
    }

    public function customer_location(Request $request){
        dd($request->all());

    }
}
