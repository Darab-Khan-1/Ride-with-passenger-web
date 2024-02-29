<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Arr;
use Yajra\DataTables\DataTables;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $roles = Role::all();
            return DataTables::of($roles)->make(true);
        }
        $total = Role::count();
        return view('roles', compact('total'));
    }

    public function createPermission()
    {

        // Permission::create(['name' => 'create_driver']);
        // Permission::create(['name' => 'update_driver']);
        // Permission::create(['name' => 'view_driver']);
        // Permission::create(['name' => 'delete_driver']);

        // Permission::create(['name' => 'create_trip']);
        // Permission::create(['name' => 'update_trip']);
        // Permission::create(['name' => 'view_trip']);
        // Permission::create(['name' => 'delete_trip']);

        // Permission::create(['name' => 'create_employee']);
        // Permission::create(['name' => 'update_employee']);
        // Permission::create(['name' => 'view_employee']);
        // Permission::create(['name' => 'delete_employee']);

        // Permission::create(['name' => 'create_role']);
        // Permission::create(['name' => 'update_role']);
        // Permission::create(['name' => 'view_role']);
        // Permission::create(['name' => 'delete_role']);

        // Permission::create(['name' => 'live_tracking']);
        // Permission::create(['name' => 'playback']);


        Permission::create(['name' => 'create_customer']);
        Permission::create(['name' => 'update_customer']);
        Permission::create(['name' => 'view_customer']);
        Permission::create(['name' => 'delete_customer']);


    }


function assignAllPermissionsToUser()
{
    $user = User::where('type','superadmin')->first();
    $permissions = Permission::all();
    foreach ($permissions as $permission) {
        $user->givePermissionTo($permission);
    }
}


    public function create(Request $request)
    {
        // dd($request->all());

        $role = Role::where('name', $request->name)->first();
        if ($role) {
            return redirect('/roles')->with('error', 'Role ' . $request->name . ' already exists');
        }
        $role = Role::create(['name' => $request->name]);

        // Extract and filter only the 'on' permissions
        $permissions = Arr::where($request->all(), function ($value, $key) {
            return $value === 'on';
        });

        // Get only the keys (permission names)
        $permissionNames = array_keys($permissions);

        // Assign the permissions to the role
        $role->syncPermissions($permissionNames);
        // dd(Role::with('permissions')->find($role->id));
        return redirect('roles')->with('success', 'Role created successfully');
    }

    public function get($id)
    {
        $role = Role::with('permissions')->find($id);
        // dd($role);
        return $role;
    }

    public function update(Request $request)
    {
        $role = Role::with('permissions')->find($request->role_id);
        // dd($role);  
        $role->permissions()->detach();
        
        // Extract and filter only the 'on' permissions
        $permissions = Arr::where($request->all(), function ($value, $key) {
            return $value === 'on';
        });
        
        // Get only the keys (permission names)
        // dd($request->all());
        $permissionNames = array_keys($permissions);
        // dd($permissionNames);

        // Assign the new permissions to the role
        $role->syncPermissions($permissionNames);
        
        // For debugging, you can check the updated permissions

        return redirect('roles')->with('success', $role->name . ' updated successfully');
    }

    public function delete($id){
        Employee::where('role_id',$id)->update(['role_id' => null]);
        Role::find($id)->delete();
        return redirect('roles')->with('success','Role deleted successfully');
    }
}
