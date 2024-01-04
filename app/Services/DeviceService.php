<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Helpers\Curl;
use App\Helpers\Helpers;
use App\Models\VehicleInfo;
use App\Helpers\Timezone;
use DateTimeZone;
use DateTime;
use stdClass;
use Config;

class DeviceService
{
    use Curl;
    use Timezone;

    public function getDeviceByID($device_id)
    {
        $id = $device_id;
        $sessionId = Session('cookie');
        if ($id != '') {
            $data = '?id=' . $id;
        }
        $data = static::curl('/api/devices' . $data, 'GET', $sessionId, '', array());
        $sessionId = Session('cookie');
        $veh_data = json_decode($data->response);
        return $veh_data;
    }

    public static function deviceAdd($name)
    {
        $id = '-1';
        $uniqueId = time();
        $data = '{"id":"' . $id . '","name":"' . $name . '","uniqueId":"' . $uniqueId . '"}';
        $sessionId = session('cookie');
        $data = self::curl('/api/devices', 'POST', $sessionId, $data, array('Content-Type: application/json', 'Accept: application/json'));
        // dd($data);
        $response = json_decode($data->response);
        return $response;
    }
    public static function updateDevice($data)
    {
        $id = $data['hidden_id'];
        $name = $data['name'];
        $uniqueId = $data['uniqueId'];
        $category = $data['category'];
        $model = $data['model'];
        $phone = $data['phone'];
        $contact = $data['contact'];
        $data = '{"id":"' . $id . '","name":"' . $name . '","uniqueId":"' . $uniqueId . '","category":"' . $category . '","model":"' . $model . '","phone":"' . $phone . '","contact":"' . $contact . '"}';
        $sessionId = session('cookie');
        return self::curl('/api/devices/' . $id, 'PUT', $sessionId, $data, array('Content-Type: application/json', 'Accept: application/json'));
    }

    public function delete($id)
    {
        $sessionId = Session('cookie');
        $deleted =  static::curl('/api/devices/' . $id, 'DELETE', $sessionId, '', array('Content-Type: application/json', 'Accept: application/json'));
        if ($deleted->responseCode == 204) {
            return response()->json(['result' => true]);
        }
        return response()->json(['result' => false]);
    }



    public function allLive()
    {

        $sessionId = Session('cookie');
        $position =  static::curl('/api/positions', 'GET', $sessionId, '', array('Content-Type: application/json', 'Accept: application/json'));
        // dd($position);
        return json_decode($position->response);
    }

    public function live($id)
    {

        $sessionId = Session('cookie');
        $position =  static::curl('/api/positions?deviceId=' . $id, 'GET', $sessionId, '', array('Content-Type: application/json', 'Accept: application/json'));
        return json_decode($position->response);
    }
    public function playback($id, $from, $to)
    {
        // $isCDT = static::isCDT();
        // if($isCDT){
        //     $timeDifference = 5;
        // }else{
        //     $timeDifference = 6;
        // }
        $to = new DateTime($to);
        $to->setTimezone(new DateTimeZone("UTC"));
        $from = new DateTime($from);
        $from->setTimezone(new DateTimeZone("UTC"));

        $interval = $from->diff($to);

        $from = $from->format("Y-m-d H:i:s");
        $to = $to->format("Y-m-d H:i:s");


        $sessionId = Session('cookie');
        $from = date('Y-m-d\TH:i', strtotime($from));
        $to = date('Y-m-d\TH:i', strtotime($to));

        $from = $from . ":00Z";
        $to = $to . ":00Z";


        $hours = $interval->h; // Hours
        $minutes = $interval->i; // Minutes

        $data = 'deviceId=' . $id . '&from=' . $from . '&to=' . $to;
        $stops = static::curl('/api/reports/stops?' . $data, 'GET', $sessionId, '', array('Content-Type: application/json', 'Accept: application/json'));
        // dd($data);

        // dd($data);
        // $data = json_decode($data->response);
        // $duration = array();
        $latlongs = array();
        // $total_rec = count((array)$data);
        $index = 0;
        // foreach ($data as $key => $trips) {
        //     $stop_time = 0;
        //     if ($total_rec - 1 > $key) {
        //         $datetime1 = new DateTime($data[$key]->endTime);
        //         $datetime2 = new DateTime($data[$key + 1]->startTime);
        //         $interval = $datetime1->diff($datetime2);
        //         $stop_time = $interval->format('%h hours %i minutes');
        //     }
        //     $datetime1 = new DateTime($trips->startTime);
        //     $datetime2 = new DateTime($trips->endTime);
        //     $interval = $datetime1->diff($datetime2);
        //     $elapsed = $interval->format('Moving Time: %h hours %i minutes');
        //     $from = date('Y-m-d\TH:i', strtotime($trips->startTime));
        //     $from = $from . ":00Z";
        //     $to = date('Y-m-d\TH:i', strtotime($trips->endTime));
        //     $to = $to . ":00Z";
        $routes = 'deviceId=' . $id . '&from=' . $from . '&to=' . $to;
        $routes = static::curl('/api/reports/route?' . $routes, 'GET', $sessionId, '', array('Content-Type: application/json', 'Accept: application/json'));
        $routes = json_decode($routes->response);
        // print_r($routes);die();
        // dd($routes);
        foreach ($routes as $i => $value) {

            $latlongs[$index]['trip_no'] = 0;
            $latlongs[$index]['stop_time'] = "<br>Stop Duration: " . "stop_time";
            $latlongs[$index]['date_time'] = "<br>Trip Start Time: " .  date('Y-m-d H:i:s', strtotime($value->serverTime));
            $latlongs[$index]['duration'] = $hours . " hrs " . $minutes . " mins";
            $latlongs[$index]['latitude'] = $value->latitude;
            $latlongs[$index]['longitude'] = $value->longitude;
            $latlongs[$index]['speed'] = $value->speed;
            $latlongs[$index]['address'] = $value->address;
            $latlongs[$index]['updatedTime'] = $value->serverTime ? date('Y-m-d H:i:s', strtotime($value->serverTime)) : '';
            $latlongs[$index]['course'] = $value->course;
            $index++;
        }
        $stops = json_decode($stops->response);
        foreach ($stops as &$value) {
            $value->startTime = date('Y-m-d H:i:s', strtotime($value->startTime));
            $value->endTime = date('Y-m-d H:i:s', strtotime($value->endTime));
            $value->duration = number_format($value->duration / 3600000, 0) . " hrs " . number_format(($value->duration / 1000) % 60, 0) . " mins";
        }
        $response['stops']  = $stops;
        $response['latlong'] = $latlongs;
        return $response;
    }
}
