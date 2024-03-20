<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function advertisementIndex(){
        $add = Setting::where('name' ,'advertisement')->first();
        if($add == null){
            $add = new Setting();
            $add->name = 'advertisement';
            $add->value = '-';
            $add->save();
        }
        return view('settings.advertisement',compact('add'));
    }

    public function saveAdvertisement(Request $request){
        // dd($request->all());
        $add = Setting::where('name','advertisement')->first();
        $add->value = $request->addvertisement;
        $add->save();
        return redirect('advertisement')->with('success','Saved Successfully');
    }
}
