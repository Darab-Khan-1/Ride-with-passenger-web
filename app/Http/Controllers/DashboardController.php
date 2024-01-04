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
        $data['active'] = Trip::where('completed_at',null)->count();
        $data['completed'] = Trip::where('completed_at','!=',null)->count();
        return view('dashboard',compact('data'));
    }
}
