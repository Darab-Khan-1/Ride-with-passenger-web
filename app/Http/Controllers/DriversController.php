<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Trip;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use App\Services\DeviceService;
use Intervention\Image\Facades\Image;
use App\Helpers\Timezone;
use GuzzleHttp\RedirectMiddleware;
use App\Services\NotificationService;
use PDO;
use stdClass;

class DriversController extends Controller
{
    use Timezone;
    protected $DeviceService;
    public function __construct(DeviceService $DeviceService)
    {
        $this->DeviceService = $DeviceService;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $drivers = Driver::where('id', '>', 0)->with('user')->get();
            return DataTables::of($drivers)->make(true);
        }
        $total = Driver::count();
        return view('drivers', compact('total'));
    }
    public function create(Request $request)
    {
        // dd($request->all()); 
        $existing = User::where('email', $request->email)->first();
        if ($existing) {
            return redirect()->back()->with('error', 'Email already exists');
        }
        $user = new User();
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->type = 'driver';
        $user->save();

        $driver = new Driver();
        $driver->name = $request->name;
        $driver->phone = $request->phone;
        $driver->license_no = $request->license_no;
        $driver->user_id = $user->id;
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
            $driver_avatar = asset('/storage/users') . '/' . $imageFileName;
        } else {
            $driver_avatar = url('assets/media/users/blank.png');
        }

        $pattern = "/\/admin\.\b/";
        $replacement = "/app.";

        if (preg_match($pattern, $driver_avatar)) {
            // If it exists, replace it
            $result = preg_replace($pattern, $replacement, $driver_avatar);
            $driver->avatar = $result;
        } else {
            $driver->avatar = $driver_avatar;
        }
        $device_id = $this->DeviceService->deviceAdd($request->name);
        if ($device_id != null && isset($device_id->id)) {
            $driver->device_id = $device_id->id;
            $driver->unique_id = $device_id->uniqueId;
            $driver->save();
        } else {
            return redirect()->back()->with('error', 'Something went wrong. Try login again');
        }

        return redirect('drivers')->with('success', 'Driver created successfully');
    }

    public function get($id)
    {
        return Driver::where('user_id', $id)->with('user')->first();
    }

    public function update(Request $request)
    {
        // dd($request->all());

        $existing = User::where('email', $request->email)->where('id', '!=', $request->user_id)->first();
        if ($existing) {
            return redirect()->back()->with('error', 'Email already exists');
        }
        $user = User::find($request->user_id);
        $user->email = $request->email;
        $user->save();

        $driver = Driver::where('user_id', $request->user_id)->first();
        $driver->name = $request->name;
        $driver->phone = $request->phone;
        $driver->license_no = $request->license_no;
        if ($request->profile_avatar_remove != null) {
            $driver_avatar =  url('assets/media/users/blank.png');
        }
        if ($request->file('profile_avatar')) {
            $imageFileName = time() . '' . rand(10, 10000) . '.' . $request->file('profile_avatar')->getClientOriginalExtension();
            $directory = public_path('storage/users');
            if (!File::isDirectory($directory)) {
                File::makeDirectory($directory, 0755, true, true);
            }
            $imagePath = $directory . '/' . $imageFileName;
            $image = Image::make($request->file('profile_avatar'));
            $side = max($image->width(), $image->height());
            $background = Image::canvas($side, $side, '#ffffff'); // Create a white canvas.
            $background->insert($image, 'center');
            $background->save($imagePath);
            $driver_avatar = asset('/storage/users') . '/' . $imageFileName;

            $pattern = "/\/admin\.\b/";
            $replacement = "/app.";
            if (preg_match($pattern, $driver_avatar)) {
                // If it exists, replace it
                $result = preg_replace($pattern, $replacement, $driver_avatar);
                $driver->avatar = $result;
            } else {
                $driver->avatar = $driver_avatar;
            }
        }
        $driver->save();

        return redirect('drivers')->with('success', 'Driver updated successfully');
    }

    // public function approve($id)
    // {
    //     $driver = Driver::where('user_id', $id)->first();
    //     $driver->approved = 1;
    //     $driver->save();
    //     return redirect('drivers')->with('success', $driver->name . ' is approved now!');
    // }

    public function changePassword(Request $request)
    {
        // dd($request->all());
        $user = User::find($request->user_id);
        $user->password = Hash::make($request->password);
        $user->save();
        return redirect()->back()->with('success', 'Password changed');
    }
    public function delete($id)
    {
        $driver = Driver::where('user_id', $id)->first();
        $driver->delete();

        $user = User::find($id);
        $user->email = $user->email . "-removed" . $id;
        $user->save();
        $user->delete();
        return redirect('drivers')->with('success', 'Driver deleted successfully');
    }



    // Tracking

    public function allLocations()
    {
        $position = $this->DeviceService->allLive();
        // dd($position);
        $drivers = Driver::where('id', '>', 0)->get();
        $response = [];
        foreach ($position as $pos) {
            foreach ($drivers as $value) {
                if ($value->device_id == $pos->deviceId) {
                    $data = new stdClass();
                    $data->name = $value->name;
                    $data->phone = $value->phone;
                    $data->avatar = $value->avatar;
                    $data->device_id = $value->device_id;
                    $data->latitude = $pos->latitude;
                    $data->longitude = $pos->longitude;
                    $data->speed = $pos->speed;
                    $data->attributes = ($pos->attributes);
                    $data->address = $pos->address;
                    $data->course = $pos->course;
                    $data->serverTime = date('h:i A d M, Y', strtotime($pos->serverTime));
                    array_push($response, $data);
                }
            }
        }
        return $response;
    }
    public function live(Request $request, $id = null)
    {
        if ($request->ajax()) {
            // $isCDT = static::isCDT();
            // if($isCDT){
            //     $timeDifference = 5;
            // }else{
            //     $timeDifference = 6;
            // }
            $data['driver'] = Driver::where('device_id',$id)->first();
            $data['position'] = $position = $this->DeviceService->live($id);
            // dd($position);
            if (isset($position[0])) {
                $position[0]->serverTime = date('h:i A d M, Y', strtotime($position[0]->serverTime));
                $data['position'] = $position[0];
            }
            return $data;
        }
        $drivers = Driver::all();
        $positions = $this->DeviceService->allLive();
        // dd($positions);
        foreach($drivers as &$driver){
            $driver->address = "N/A";
            foreach($positions as $value){
                if($value->deviceId == $driver->device_id){
                    $driver->address = $value->address != null ? $value->address : "N/A";
                }
            }
        }
        // dd($drivers);
        return view('live', compact('drivers', 'id'));
    }

    public function playbackIndex($service_id)
    {
        $service = null;
        if ($service_id != 0) {
            $service = Trip::where('id', $service_id)->with('driver')->first();
        }
        $drivers = Driver::all();
        return view('playback', compact('drivers', 'service'));
    }

    public function playback($id, $from, $to)
    {
        $response = $this->DeviceService->playback($id, $from, $to);
        return $response;
    }
    public function customNotification(Request $request,$driverId) {
        $this->validate($request,[
            'notification'=>'required',
            'title'=>'required'
         ],[
            'notification.required'=>'Notification message is required',
            'title.required'=>'Title is required',
         ]);
         $data=[
            'title'=>$request->title,
            'message'=>$request->notification,
            'sound'=>'notificationfromplatform.mp3',
         ];
         $driver=User::find($driverId);
         if($driver->fcm_token!=null){
            (new NotificationService)->sendNotification($driver->fcm_token,$data,'admin');
         }
         Notification::create(['title'=>$request->title,
                        'notification'=>$request->notification,
                        'type'=>'message',
                        'user_id'=>$driver->id,
                        'seen'=>0,
                        ]);
         return back()->with(['success'=>'Successfully notification sent!']);
    }
}
