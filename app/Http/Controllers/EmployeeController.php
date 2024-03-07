<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use Google\Service\DatabaseMigrationService\FunctionEntity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

use function PHPUnit\Framework\returnSelf;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        if ($request->all()) {
            $employees = Employee::where('id', ">", 0)->with('user', 'role')->get();
            return DataTables::of($employees)->make(true);
        }
        $total = Employee::count();
        $roles =  Role::all();
        return view('employees', compact('total', 'roles'));
    }

    public function create(Request $request)
    {


        $user = new User();
        $user->email = $request->email;
        $user->type = 'employee';
        $user->password = Hash::make($request->password);
        $user->save();

        $role = Role::find($request->role);
        if ($role)
            $user->assignRole($role);
        // dd($request->all());
        $employee = new Employee();
        $employee->name = $request->name;
        $employee->user_id = $user->id;
        $employee->role_id = $request->role;
        $employee->address = $request->address;
        $employee->phone = $request->phone;

        if ($request->file('profile_avatar')) {
            $imageFileName = time() . '' . rand(10, 10000) . '.' . $request->file('profile_avatar')->getClientOriginalExtension();
            $directory = public_path('storage/users');
            if (!File::isDirectory($directory)) {
                File::makeDirectory($directory, 0755, true, true);
            }
            $imagePath = $directory . '/' . $imageFileName;
            $image = Image::make($request->file('profile_avatar'));
            $side = max($image->width(), $image->height());
            $background = Image::canvas($side, $side, '#ffffff');
            $background->insert($image, 'center');
            $background->save($imagePath);
            $employee->avatar = asset('/storage/users') . '/' . $imageFileName;
        } else {
            $employee->avatar = url('assets/media/users/blank.png');
        }
        $employee->save();
        return redirect('/employees')->with('success', __('messages.employee_created_successfully'));
    }

    public function get($id)
    {
        // dd($id);
        $employee = Employee::where('user_id', $id)->with('user', 'role')->first();
        // dd($employee);
        return $employee;
    }


    public function update(Request $request)
    {

        $user = User::where('email',$request->email)->whereNot('id',$request->user_id)->first();
        if($user){
            return redirect()->back()->with('error', __('messages.email_already_exists'));
        }
        // dd($request->all());
        $user = User::find($request->user_id);
        $user->email = $request->email;
        $user->save();
        $user->removeAllRoles();

        $role = Role::find($request->role);
        if ($role) {
            $user->syncRoles([$role]);
        }
        // dd($request->all());
        $employee = Employee::where('user_id',$request->user_id)->first();
        $employee->name = $request->name;
        $employee->role_id = $request->role;
        $employee->address = $request->address;
        $employee->phone = $request->phone;

        if ($request->file('profile_avatar')) {
            $imageFileName = time() . '' . rand(10, 10000) . '.' . $request->file('profile_avatar')->getClientOriginalExtension();
            $directory = public_path('storage/users');
            if (!File::isDirectory($directory)) {
                File::makeDirectory($directory, 0755, true, true);
            }
            $imagePath = $directory . '/' . $imageFileName;
            $image = Image::make($request->file('profile_avatar'));
            $side = max($image->width(), $image->height());
            $background = Image::canvas($side, $side, '#ffffff');
            $background->insert($image, 'center');
            $background->save($imagePath);
            $employee->avatar = asset('/storage/users') . '/' . $imageFileName;
        } else {
            if ($request->profile_avatar_remove != null)
                $employee->avatar = url('assets/media/users/blank.png');
        }
        $employee->save();
        return redirect('/employees')->with('success', __('messages.employee_updated_successfully'));
    }

    public function delete($id) {
        $employee = Employee::where('user_id', $id)->first();
        $employee->delete();

        $user = User::find($id);
        $user->email = $user->email . "-remove" . $id;
        $user->save();
        $user->delete();
        return redirect('/employees')->with('success', __('messages.employee_deleted')); 
    }
}
