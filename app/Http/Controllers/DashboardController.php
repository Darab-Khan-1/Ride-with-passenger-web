<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Driver;
use App\Models\Trip;

class DashboardController extends Controller
{
    public function index(){
        $data['drivers'] = Driver::count();
        $data['trips'] = Trip::count();
        $data['available'] = Trip::whereNull('status')->count();
        $data['active'] = Trip::whereNotIn('status',['available','completed'])->whereNotNull('status')->count();
        $data['completed'] = Trip::where('status','completed')->count();
        $data['incomplete'] = Trip::where('status',null)->count();
        return view('dashboard',compact('data'));
    }
}
