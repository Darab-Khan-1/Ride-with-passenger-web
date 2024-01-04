<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ValidationRules;
use App\Http\Requests\ValidationMessages;
use App\Http\Requests\Validate;
use App\Http\Resources\ErrorResource;
use App\Models\Trip;
use Illuminate\Support\Facades\DB;
use App\Helpers\Curl;
use App\Models\Driver;
use App\Services\DeviceService;
use Illuminate\Support\Facades\Config;
use stdClass;

class TripController extends Controller
{
    use curl;

    protected $rules;
    protected $validationMessages;
    protected $DeviceService;

    public function __construct(ValidationRules $rules, ValidationMessages $validationMessages, DeviceService $DeviceService)
    {
        $this->rules = $rules;
        $this->validationMessages = $validationMessages;
        $this->DeviceService = $DeviceService;
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
            $trip = Trip::where('user_id', $request->user()->id)->with('stops')->first();
            if ($trip == null) {
                return $this->apiJsonResponse(200, "No ongoing trip found!", '', "");
            }
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
            $data->drop_lng = $trip->drop_lng;
            $data->drop_long = $trip->drop_long;
            $data->stops = $trip->stops;
            return $this->apiJsonResponse(200, "Trip found!", $data, "");
        } catch (\Throwable $e) {
            return $this->apiJsonResponse(400, "Something went wrong", '', $e->getMessage());
        }
    }

    public function all(Request $request)
    {
        try {
            $trips = Trip::where('user_id', $request->user()->id)->with('stops')->get();
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
                $data->drop_lng = $value->drop_lng;
                $data->drop_long = $value->drop_long;
                $data->stops = $value->stops;
                array_push($response, $data);
            }
            return $this->apiJsonResponse(200, "Trips found!", $response, "");
        } catch (\Throwable $e) {
            return $this->apiJsonResponse(400, "Something went wrong", '', $e->getMessage());
        }
    }
}
