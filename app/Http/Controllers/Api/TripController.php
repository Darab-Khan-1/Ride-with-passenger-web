<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ValidationRules;
use App\Http\Requests\ValidationMessages;
use App\Http\Requests\Validate;
use App\Http\Resources\ErrorResource;
use App\Models\Trip;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Helpers\Curl;
use App\Models\Stop;
use App\Services\DeviceService;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Config;
use Lcobucci\JWT\Validation\ValidAt;
use stdClass;

class TripController extends Controller
{
    use curl;

    protected $rules;
    protected $validationMessages;
    protected $DeviceService;
    protected $NotificationService;

    public function __construct(ValidationRules $rules, ValidationMessages $validationMessages, DeviceService $DeviceService, NotificationService $NotificationService)
    {
        $this->rules = $rules;
        $this->validationMessages = $validationMessages;
        $this->DeviceService = $DeviceService;
        $this->NotificationService = $NotificationService;
    }

    public function apiJsonResponse($code, $message, $data, $error)
    {
        $response = new stdClass();
        $response->status_code = $code;
        $response->message = $message;
        $response->error = $error;
        $response->data = $data;
        return response()->json($response, $response->status_code);
    }


    public function adminLogin()
    {
        $adminEmail = Config::get('constants.Constants.adminEmail');
        $adminPassword = Config::get('constants.Constants.adminPassword');
        $data = 'email=' . $adminEmail . '&password=' . $adminPassword;
        $response = static::curl('/api/session', 'POST', '', $data, array(Config::get('constants.Constants.urlEncoded')));
        $res = json_decode($response->response);
        // dd($response);
    }

    public function ongoing(Request $request)
    {
        try {
            $trip = Trip::where('user_id', $request->user()->id)->whereNotNull('started_at')->whereNull('completed_at')->with('stops', 'attributes')->first();
            if ($trip == null) {
                return $this->apiJsonResponse(200, "No ongoing trip found!", '', "");
            }
            $array = [];
            $data = new stdClass();
            $data->id = $trip->id;
            $data->unique_id = $trip->unique_id;
            $data->pickup_location = $trip->pickup_location;
            $data->pickup_date = $trip->pickup_date;
            $data->delivery_date = $trip->delivery_date;
            $data->delivery_location = $trip->delivery_location;
            $data->estimated_distance = $trip->estimated_distance;
            $data->estimated_time = $trip->estimated_time;
            $data->customer_name = $trip->customer_name;
            $data->customer_phone = $trip->customer_phone;
            $data->lat = $trip->lat;
            $data->long = $trip->long;
            $data->drop_lat = $trip->drop_lat;
            $data->drop_long = $trip->drop_long;
            $data->status = (($trip->status == "pickup" || stripos($trip->status, 'stop') !== false) ? 'stopped' : $trip->status);
            $data->stops = $trip->stops;
            $data->url = url('live/share/location', $trip->slug);
            $data->attributes = $trip->attributes;
            $data->current_stop = 0;
            if ($trip->status == 'pickup') {
                // dd($trip->stops[0]->id);
                $data->current_stop = $trip->stops[0]->id;
            } elseif (stripos($trip->status, 'stop') !== false) {
                foreach ($trip->stops as $key => $value) {
                    if ($value->datetime != null && $value->exit_time == null) {
                        $data->current_stop = $value->id;

                        break;
                    }
                }
            }
            if ($trip->status == 'started') {
                $array['stop'] = 1;
                $array['stop_id'] = 0;
                $array['type'] = "pickup";
                $array['lat'] = $trip->lat;
                $array['long'] = $trip->long;
                $array['address'] = $trip->pickup_location;
                $array['description'] = $trip->stops[0]->description;
                $data->next_stop = $array;
            } else if (count($trip->stops) > 2) {
                $array['stop'] = 1;
                $array['stop_id'] = $trip->stops[count($trip->stops) - 1]->id;
                $array['type'] = "destination";
                $array['lat'] = $trip->drop_lat;
                $array['long'] = $trip->drop_long;
                $array['address'] = $trip->delivery_location;
                $array['description'] = $trip->stops[count($trip->stops) - 1]->description;
                $data->next_stop = $array;
                foreach ($trip->stops as $key => $value) {
                    if ($value->datetime == null) {
                        $array['stop'] = $key + 1;
                        $array['stop_id'] = $value->id;
                        $array['type'] = $value->type;
                        $array['lat'] = $value->lat;
                        $array['long'] = $value->long;
                        $array['address'] = $value->location;
                        $array['description'] = $value->description;
                        $data->next_stop = $array;
                        break;
                    }
                }
            } else {
                $array['stop'] = 1;
                $array['stop_id'] = $trip->stops[count($trip->stops) - 1]->id;
                $array['type'] = "destination";
                $array['lat'] = $trip->drop_lat;
                $array['long'] = $trip->drop_long;
                $array['address'] = $trip->delivery_location;
                $array['description'] = $trip->stops[count($trip->stops) - 1]->description;
                $data->next_stop = $array;
            }
            return $this->apiJsonResponse(200, "Trip found!", $data, "");
        } catch (\Throwable $e) {
            return $this->apiJsonResponse(400, "Something went wrong", '', $e->getMessage());
        }
    }

    public function all(Request $request)
    {
        try {
            $trips = Trip::where('user_id', $request->user()->id)->whereNull('started_at')->where('status','available')->with('stops', 'attributes')->get();
            $response = [];
            foreach ($trips as $value) {
                $data = new stdClass();
                $data->id = $value->id;
                $data->unique_id = $value->unique_id;
                $data->pickup_location = $value->pickup_location;
                $data->pickup_date = $value->pickup_date;
                $data->delivery_date = $value->delivery_date;
                $data->delivery_location = $value->delivery_location;
                $data->estimated_distance = $value->estimated_distance;
                $data->estimated_time = $value->estimated_time;
                $data->customer_name = $value->customer_name;
                $data->customer_phone = $value->customer_phone;
                $data->lat = $value->lat;
                $data->long = $value->long;
                $data->drop_lat = $value->drop_lat;
                $data->drop_long = $value->drop_long;
                $data->stops = $value->stops;
                $data->attributes = $value->attributes;
                array_push($response, $data);
            }
            return $this->apiJsonResponse(200, "Trips found!", $response, "");
        } catch (\Throwable $e) {
            return $this->apiJsonResponse(400, "Something went wrong", '', $e->getMessage());
        }
    }

    public function my(Request $request)
    {
        try {
            $trips = Trip::where('user_id', $request->user()->id)->whereNull('started_at')->where('status', 'accepted')->with('stops', 'attributes')->get();
            $response = [];
            foreach ($trips as $value) {
                $data = new stdClass();
                $data->id = $value->id;
                $data->unique_id = $value->unique_id;
                $data->pickup_location = $value->pickup_location;
                $data->pickup_date = $value->pickup_date;
                $data->delivery_date = $value->delivery_date;
                $data->delivery_location = $value->delivery_location;
                $data->estimated_distance = $value->estimated_distance;
                $data->estimated_time = $value->estimated_time;
                $data->customer_name = $value->customer_name;
                $data->customer_phone = $value->customer_phone;
                $data->lat = $value->lat;
                $data->long = $value->long;
                $data->drop_lat = $value->drop_lat;
                $data->drop_long = $value->drop_long;
                $data->stops = $value->stops;
                $data->url = url('live/share/location', $value->slug);
                $data->attributes = $value->attributes;
                array_push($response, $data);
            }
            return $this->apiJsonResponse(200, "Trips found!", $response, "");
        } catch (\Throwable $e) {
            return $this->apiJsonResponse(400, "Something went wrong", '', $e->getMessage());
        }
    }


    public function accept(Request $request, Validate $validate)
    {
        $validationErrors = $validate->validate($request, $this->rules->acceptTripValidationRules(), $this->validationMessages->acceptTripValidationMessages());
        if ($validationErrors) {
            return (new ErrorResource($validationErrors))->response()->setStatusCode(400);
        }
        try {
            $trip = Trip::find($request->trip_id);
            $trip->status = $request->status;
            if ($request->status == 'rejected')
                $trip->user_id = null;
            $trip->save();
            $data = [
                'message' => $trip->unique_id . ' has been ' . $request->status,
                'title' => 'Trip Update',
                'sound' => 'anychange.mp3',
            ];
            $this->sendAdminNotification($data);
            return $this->apiJsonResponse(200, "Trip status updated!", '', "");
        } catch (\Throwable $e) {
            return $this->apiJsonResponse(400, "Something went wrong", '', $e->getMessage());
        }
    }

    public function completed(Request $request)
    {
        try {
            $trips = Trip::where('user_id', $request->user()->id)->whereNotNull('completed_at')->with('stops', 'attributes')->get();
            $response = [];
            foreach ($trips as $value) {
                $data = new stdClass();
                $data->id = $value->id;
                $data->unique_id = $value->unique_id;
                $data->pickup_location = $value->pickup_location;
                $data->pickup_date = $value->pickup_date;
                $data->delivery_date = $value->delivery_date;
                $data->delivery_location = $value->delivery_location;
                $data->estimated_distance = $value->estimated_distance;
                $data->estimated_time = $value->estimated_time;
                $data->customer_name = $value->customer_name;
                $data->customer_phone = $value->customer_phone;
                $data->lat = $value->lat;
                $data->long = $value->long;
                $data->drop_lat = $value->drop_lat;
                $data->drop_long = $value->drop_long;
                $data->stops = $value->stops;
                $data->attributes = $value->attributes;
                array_push($response, $data);
            }
            return $this->apiJsonResponse(200, "Trips found!", $response, "");
        } catch (\Throwable $e) {
            return $this->apiJsonResponse(400, "Something went wrong", '', $e->getMessage());
        }
    }


    public function start(Request $request, Validate $validate)
    {
        $validationErrors = $validate->validate($request, $this->rules->startTripValidationRules(), $this->validationMessages->startTripValidationMessages());
        if ($validationErrors) {
            return (new ErrorResource($validationErrors))->response()->setStatusCode(400);
        }
        try {
            $trip = Trip::where('user_id', $request->user()->id)->whereNotNull('started_at')->whereNull('completed_at')->first();
            if ($trip != null) {
                return $this->apiJsonResponse(400, "You have an ongoing trip. Please end it first", '', "");
            }
            $trip = Trip::find($request->trip_id);
            if ($trip->status != 'accepted') {
                return $this->apiJsonResponse(400, "This trip cannot be started", '', "");
            }
            $trip->started_at = date('Y-m-d H:i:s', strtotime('now'));
            $trip->status = 'started';
            $trip->save();
            $data = [
                'message' => $trip->unique_id . ' has been started',
                'title' => 'Trip Update',
                'sound' => 'anychange.mp3',
            ];
            $this->sendAdminNotification($data);
            return $this->apiJsonResponse(200, "Trip started!", '', "");
        } catch (\Throwable $e) {
            return $this->apiJsonResponse(400, "Something went wrong", '', $e->getMessage());
        }
    }


    public function addStop(Request $request, Validate $validate)
    {
        $validationErrors = $validate->validate($request, $this->rules->addStopValidationRules(), $this->validationMessages->addStopValidationMessages());
        if ($validationErrors) {
            return (new ErrorResource($validationErrors))->response()->setStatusCode(400);
        }
        try {
            // $trip = Trip::find($request->trip_id);

            $stops = Stop::where('trip_id',$request->trip_id)->get();

            Stop::where('trip_id',$request->trip_id)->whereNull('datetime')->delete();

            $stop = new Stop();
            $stop->trip_id = $request->trip_id;
            $stop->location = $request->location;
            $stop->lat = $request->lat;
            $stop->long = $request->long;
            $stop->type = 'stop';
            $stop->description = $request->description;
            $stop->save();

            foreach ($stops as $value){
                if($value->datetime == null){
                    $stop = new Stop();
                    $stop->trip_id = $request->trip_id;
                    $stop->location = $value->location;
                    $stop->lat = $value->lat;
                    $stop->long = $value->long;
                    $stop->type = $value->type;
                    $stop->description = $value->description;
                    $stop->save();
                }
            }
            
            return $this->apiJsonResponse(200, "Stop added!", '', "");
        } catch (\Throwable $e) {
            return $this->apiJsonResponse(400, "Something went wrong", '', $e->getMessage());
        }
    }



    public function delete(Request $request, Validate $validate)
    {
        $validationErrors = $validate->validate($request, $this->rules->deleteStopValidationRules(), $this->validationMessages->deleteStopValidationMessages());
        if ($validationErrors) {
            return (new ErrorResource($validationErrors))->response()->setStatusCode(400);
        }
        try {
            $stop  = Stop::find($request->stop_id);
            if ($stop->type != 'stop') {
                return $this->apiJsonResponse(400, "Stop cannot be pickup or dropoff point!", '', "");
            }
            $stop->delete();
            return $this->apiJsonResponse(200, "Stop deleted!", '', "");
        } catch (\Throwable $e) {
            return $this->apiJsonResponse(400, "Something went wrong", '', $e->getMessage());
        }
    }

    public function pickup(Request $request, Validate $validate)
    {
        $validationErrors = $validate->validate($request, $this->rules->startTripValidationRules(), $this->validationMessages->startTripValidationMessages());
        if ($validationErrors) {
            return (new ErrorResource($validationErrors))->response()->setStatusCode(400);
        }
        try {
            // $trip = Trip::where('user_id', $request->user()->id)->whereNotNull('started_at')->whereNull('completed_at')->first();
            // if ($trip != null) {
            //     return $this->apiJsonResponse(400, "You have an ongoing trip. Please end it first", '', "");
            // }
            $trip = Trip::find($request->trip_id);
            if ($trip->status != 'started') {
                return $this->apiJsonResponse(400, "This trip cannot be updated", '', "");
            }
            // $trip->started_at = date('Y-m-d H:i:s', strtotime('now'));
            $trip->status = 'pickup';
            $trip->save();
            $stop = Stop::where('type', 'pickup')->where('trip_id', $request->trip_id)->first();
            $stop->datetime = date('Y-m-d H:i:s', strtotime('now'));
            $stop->save();
            $data = [
                'message' => $trip->unique_id . " is now at " . strtoupper($stop->type) . ": " . $stop->location,
                'title' => 'Trip Update',
                'sound' => 'anychange.mp3',
            ];
            $this->sendAdminNotification($data);
            return $this->apiJsonResponse(200, "Trip status updated!", '', "");
        } catch (\Throwable $e) {
            return $this->apiJsonResponse(400, "Something went wrong", '', $e->getMessage());
        }
    }


    public function stop(Request $request, Validate $validate)
    {
        $validationErrors = $validate->validate($request, $this->rules->stopTripValidationRules(), $this->validationMessages->stopTripValidationMessages());
        if ($validationErrors) {
            return (new ErrorResource($validationErrors))->response()->setStatusCode(400);
        }
        try {
            $stop = Stop::where('id', $request->stop_id)->where('trip_id', $request->trip_id)->first();
            if ($stop == null) {
                return $this->apiJsonResponse(400, "Trip not found", '', "");
            }
            if ($stop->datetime != null) {
                return $this->apiJsonResponse(400, "Status already updated for this stop", '', "");
            }
            $trip = Trip::where('id', $request->trip_id)->with('stops')->first();
            if ($trip->user_id != $request->user()->id || $trip->status == 'available') {
                return $this->apiJsonResponse(404, "This trip status cannot be updated", '', "");
            }
            // $allStops = Stop::where('trip_id', $stop->trip_id)->get();
            foreach ($trip->stops as $key => $value) {
                if ($request->stop_id == $value->id) {
                    if (($key + 1) ==  count($trip->stops)) {
                        $trip->status = 'destination';
                    } else {
                        $trip->status = 'stop ' . ($key);
                    }
                    $trip->save();
                    break;
                }
            }
            $stop->datetime = date('Y-m-d H:i:s', strtotime('now'));
            if ($request->lat) {
                $stop->lat = $request->lat;
            }
            if ($request->long) {
                $stop->long = $request->long;
            }
            if ($request->location) {
                $stop->location = $request->location;
            }
            $stop->save();
            $data = [
                'message' => $trip->unique_id .  ' is now at stop:' . $stop->location ,
                'title' => 'Trip Update',
                'sound' => 'anychange.mp3',
            ];
            $this->sendAdminNotification($data);
            return $this->apiJsonResponse(200, "Status updated!", '', "");
        } catch (\Throwable $e) {
            return $this->apiJsonResponse(400, "Something went wrong", '', $e->getMessage());
        }
    }

    public function exit(Request $request, Validate $validate)
    {
        $validationErrors = $validate->validate($request, $this->rules->stopTripValidationRules(), $this->validationMessages->stopTripValidationMessages());
        if ($validationErrors) {
            return (new ErrorResource($validationErrors))->response()->setStatusCode(400);
        }
        try {
            $stop = Stop::where('id', $request->stop_id)->where('trip_id', $request->trip_id)->first();
            if ($stop == null) {
                return $this->apiJsonResponse(400, "Stop not found", '', "");
            }
            if ($stop->exit_time != null) {
                return $this->apiJsonResponse(400, "Status already updated for this stop", '', "");
            }
            $trip = Trip::where('id', $request->trip_id)->with('stops')->first();
            if ($trip->user_id != $request->user()->id || $trip->status == 'available') {
                return $this->apiJsonResponse(404, "This trip status cannot be updated", '', "");
            }
            // $allStops = Stop::where('trip_id', $stop->trip_id)->get();
            foreach ($trip->stops as $key => $value) {
                if ($request->stop_id == $value->id) {
                    $trip->status = "in-transit";
                    $trip->save();
                    break;
                }
            }
            $stop->exit_time = date('Y-m-d H:i:s', strtotime('now'));
            $stop->save();
            $data = [
                'message' =>  $trip->unique_id .  ' exited ' . strtoupper($stop->type) . ": " . $stop->location,
                'title' => 'Trip Update',
                'sound' => 'anychange.mp3',
            ];
            $this->sendAdminNotification($data);
            return $this->apiJsonResponse(200, "Status updated!", '', "");
        } catch (\Throwable $e) {
            return $this->apiJsonResponse(400, "Something went wrong", '', $e->getMessage());
        }
    }


    public function end(Request $request, Validate $validate)
    {
        $validationErrors = $validate->validate($request, $this->rules->startTripValidationRules(), $this->validationMessages->startTripValidationMessages());
        if ($validationErrors) {
            return (new ErrorResource($validationErrors))->response()->setStatusCode(400);
        }
        try {
            $trip = Trip::find($request->trip_id);
            if ($trip->status == 'available' || $trip->status == 'completed') {
                return $this->apiJsonResponse(400, "Cannot end this trip", '', "");
            }
            $trip->completed_at = date('Y-m-d H:i:s', strtotime('now'));
            $trip->status = 'completed';
            $trip->save();
            $stop = Stop::where('trip_id', $request->trip_id)->where('type', 'destination')->first();
            $stop->exit_time =  date('Y-m-d H:i:s', strtotime('now'));
            $stop->save();
            $data = [
                'message' =>  $trip->unique_id .  ' is ended at: ' . $stop->location,
                'message' => 'Trip has been ended',
                'title' => 'Trip Update',
                'sound' => 'anychange.mp3',
            ];
            $this->sendAdminNotification($data);
            return $this->apiJsonResponse(200, "Trip ended!", '', "");
        } catch (\Throwable $e) {
            return $this->apiJsonResponse(400, "Something went wrong", '', $e->getMessage());
        }
    }
    private function sendAdminNotification($data)
    {
        $users = User::where('type', 'superadmin')->select('fcm_token', 'id')->get();
        foreach ($users as $key => $user) {
            if ($user->fcm_token != null) {
                $this->NotificationService->sendNotification($user->fcm_token, $data, 'driver');
            }
        }
        Notification::create([
            'title' => $data['title'],
            'notification' => $data['message'],
            'type' => 'web',
            'seen' => 0,
        ]);
    }
}
