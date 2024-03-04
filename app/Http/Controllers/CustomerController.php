<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Customer_location;
use App\Models\CustomerLocation;
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
        return view('customers.customer', compact('total', 'roles'));
    }

    public function createCustomer()
    {
        return view('customers.create');
    }

    public function create(Request $request)
    {
        // dd($request->all());
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

        $role = Role::where('name', 'Customer')->first();
        // dd($role);
        if ($role) {
            $user->syncRoles([$role]);
        }

        $customer = new Customer();
        $customer->name = $request->customer_name;
        $customer->phone = $request->phone;
        $customer->user_id = $user->id;
        $customer->role_id = $role->id;
        $customer->address = $request->address;
        $customer->company_phone = $request->company_phone;
        $customer->company_name = $request->company;
        // dd($request->company);
        // dd($request->file('profile_avatar'));

        if ($request->file('profile_avatar')) {
            $imageName = time() . '.' . $request->file('profile_avatar')->getClientOriginalExtension();
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

        $customer_name = $request->input('name');
        $customer_latlong = $request->input('latlong');
        $customer_locations = $request->input('location');
        // dd($request->customer_id);
        foreach ($customer_latlong as $key => $value) {
            $latlng = explode(',', $value);
            $customer_location = new CustomerLocation();
            $customer_location->customer_id = $customer->id;
            $customer_location->name = $customer_name[$key];
            $customer_location->location = $customer_locations[$key];
            $customer_location->lat = $latlng[0];
            $customer_location->long = $latlng[1];
            $customer_location->save();
        }


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

    public function update(Request $request)
    {
        // dd($request->all());
        $user = User::where('email', $request->email)->whereNot('id', $request->user_id)->first();
        if ($user) {
            return redirect()->back()->with('error', 'Email already exists');
        }
        // dd($request->user_id);
        $user = User::find($request->user_id);
        // dd($user);
        $user->email = $request->email;
        // dd($user->email);
        $user->save();

        $customer = Customer::where('user_id', $request->user_id)->first();
        // dd($customer);
        $customer->name = $request->customer_name;
        // dd($customer->name);
        $customer->phone = $request->phone;
        $customer->company_phone = $request->company_phone;
        $customer->company_name = $request->company_name;
        $customer->user_id = $user->id;
        $customer->address = $request->address;

        // dd($request->profile_avatar_remove == null);
        if ($request->profile_avatar_remove != null) {
            $driver_avatar = url('assets/media/users/blank.png');
            $customer->avatar = $driver_avatar;

        }  

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
            $customer->avatar = $driver_avatar;
        }


        $customer->save();

        $customer_name = $request->input('name');
        $customer_latlong = $request->input('latlong');
        $customer_locations = $request->input('location');
        $location_ids = $request->input('location_id');
        // dd($location_ids );

        if ($location_ids == null) {
            CustomerLocation::where('customer_id', $customer->id)->delete();
        } else if (count($location_ids) > 0) {
            CustomerLocation::where('customer_id', $customer->id)->whereNotIn('id', $location_ids)->delete();
            // dd($testing);

            foreach ($location_ids as $key => $location_id) {
                // Retrieve the corresponding values from other arrays based on the index
                $value = $customer_latlong[$key] ?? null;
                $name = $customer_name[$key] ?? null;
                $location = $customer_locations[$key] ?? null;

                $latlng = explode(',', $value);
                if ($location_id == '0') {
                    $customer_location = new CustomerLocation();
                } else {
                    $customer_location = CustomerLocation::find($location_id);
                }

                $customer_location->name = $name;
                $customer_location->customer_id = $customer->id;
                $customer_location->location = $location;
                $customer_location->lat = $latlng[0] ?? null;
                $customer_location->long = $latlng[1] ?? null;
                $customer_location->save();
                // dd($customer_location);


            }
        }



        if ($customer->save()) {
            return redirect('customer')->with('success', 'Successfully updated');
        }
        return redirect('customer')->with('error', 'Some error');
    }

    public function change_password(Request $request)
    {
        $user = User::find($request->user_id);
        $user->tokens->each(function ($token, $key) {
            $token->delete();
        });
        $user->password = Hash::make($request->password);
        $user->save();
        return redirect()->back()->with('success', 'Password changed');
    }

    public function edit($id)
    {
        // $customer = Customer::find($id);
        $customer = Customer::where('id', $id)->with('locations', 'user')->first();
        return view('customers.edit', compact('customer'));
    }
}
