<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Driver;
use App\Models\User;
use App\Models\Employee;
use App\Models\Trip;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Services\NotificationService;
use App;
class DashboardController extends Controller
{
    public function index(){
        $data['roles'] = Employee::select('role_id', DB::raw('count(*) as employee_count'))->groupBy('role_id')->with('role')->get();
        $data['roles'] = Role::select('roles.*', DB::raw('COUNT(employees.id) as employee_count'))
            ->leftJoin('employees', 'roles.id', '=', 'employees.role_id')->groupBy('roles.id')->get();
        // dd($data['roles']);
        $data['drivers'] = Driver::count();
        $data['employees'] = Employee::count();
        $data['trips'] = Trip::count();
        $data['available'] = Trip::whereNull('status')->count();
        $data['active'] = Trip::whereNotIn('status',['available','completed'])->whereNotNull('status')->count();
        $data['completed'] = Trip::where('status','completed')->count();
        $data['incomplete'] = Trip::where('status',null)->count();
        // dd($data);
        return view('dashboard',compact('data'));
    }
    public function lang_change(Request $request)
    {
        
        app()->setLocale($request->lang);
        session()->put('locale', $request->lang);  
        return redirect()->back();
    }
}
