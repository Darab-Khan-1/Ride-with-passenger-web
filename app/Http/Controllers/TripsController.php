<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Stop;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use DateTime;

use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;


class TripsController extends Controller
{
    private $client;

    // public function __construct()
    // {
    //     $this->middleware(function ($request, $next) {
    //         $this->client = new Google_Client();
    //         $this->client->setAuthConfig(public_path('/credentials.json'));
    //         $this->client->setRedirectUri(url('google/auth/callback'));
    //         $this->client->addScope(Google_Service_Calendar::CALENDAR);
    //         return $next($request);
    //     });
    // }


    // public function showAuthorizationForm()
    // {
    //     $client = $this->getClient();
    //     $authUrl = $client->createAuthUrl();

    //     return view('google.auth', compact('authUrl'));
    // }


    // private function getClient()
    // {
    //     $client = new Google_Client();
    //     $client->setAuthConfig(public_path('/credentials.json')); // Path to your credentials file
    //     $client->setRedirectUri(url('google/auth/callback'));
    //     $client->setScopes([
    //         Google_Service_Calendar::CALENDAR,
    //     ]);

    //     return $client;
    // }

    // public function generateAuthorizationLink()
    // {
    //     $client = $this->getClient();
    //     $authUrl = $client->createAuthUrl();

    //     return view('google.authorization-link', compact('authUrl'));
    // }


    // public function handleAuthorizationCallback(Request $request)
    // {
    //     $client = $this->getClient();
    //     $accessToken = $client->fetchAccessTokenWithAuthCode($request->get('code'));

    //     // For testing purposes, we'll use User::find(1) to simulate an authenticated user
    //     $user = User::where('type', 'superadmin')->first();

    //     // dd($accessToken);
    //     $user->access_token = $accessToken['access_token'];
    //     $user->save();
    //     return redirect()->route('google.events.index')->with('success', 'Authorization successful!');
    // }

    // public function showEvents()
    // {
    //     $user = User::where('type', 'superadmin')->first();
    //     // dd($user->access_token);
    //     $accessToken = $user->access_token;
    //     $client = $this->getClient();
    //     $client->setAccessToken($accessToken);

    //     $service = new Google_Service_Calendar($client);
    //     $calendarId = 'rw.passengers@gmail.com';
    //     $events = $service->events->listEvents($calendarId);
    //     return view('google.events', compact('events'));
    // }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            // $trips = Trip::where('id', '>', 0)->get();

            // $user = User::where('type', 'superadmin')->first();
            // $accessToken = $user->access_token;
            // $client = $this->getClient();
            // $client->setAccessToken($accessToken);
            // $service = new Google_Service_Calendar($client);
            // $calendarId = 'rw.passengers@gmail.com';
            // foreach ($trips as $key => $value) {
            //     $eventId = $value->event_id;
            //     $event = $service->events->get($calendarId, $eventId);
            //     $startTime = $event->start->dateTime; // Returns null if the event is an all-day event
            //     $endTime = $event->end->dateTime; // Returns null if the event is an all-day event
            //     $startTime = str_replace('T', " ", $startTime);
            //     $startTime = str_replace('Z', "", $startTime);
            //     $endTime = str_replace('T', " ", $endTime);
            //     $endTime = str_replace('Z', "", $endTime);
            //     if ($startTime != $value->pickup_date || $endTime != $value->delivery_date) {
            //         Trip::where('id', $value->id)->update(['pickup_date' => $startTime, 'delivery_date' => $endTime]);
            //     }
            // }

            $trips = Trip::where('id', '>', 0)->with('stops', 'driver')->get();
            return DataTables::of($trips)->make(true);
        }
        $total = Trip::count();
        return view('trips.trips', compact('total'));
    }


    public function new()
    {
        $drivers = Driver::where('id', '>', 0)->with('user')->get();
        return view('trips.create', compact('drivers'));
    }

    public function create(Request $request)
    {
        // dd($request->all());
        $decodedJson = html_entity_decode($request->stops);
        $stops = json_decode($decodedJson, true);
        // dd($stops);
        $last = Trip::latest()->first();
        if ($last == null) {
            $unique = "GO-00001";
        } else {
            $last = $last->unique_id;
            $numericPart = substr($last, 3);
            $nextNumericPart = str_pad((int)$numericPart + 1, strlen($numericPart), '0', STR_PAD_LEFT);
            $unique = 'GO-' . $nextNumericPart;
        }
        // dd($unique);
        $trip = new Trip();
        $trip->unique_id = $unique;
        $trip->user_id = $request->user_id;
        $trip->pickup_date = $request->pickup_date;
        $trip->delivery_date = $request->delivery_date;
        $trip->pickup_location = $request->pickup_location;
        $trip->delivery_location = $request->delivery_location;
        $trip->estimated_distance = $request->estimated_distance;
        $trip->estimated_time = $request->estimated_time;
        $trip->customer_name = $request->customer_name;
        $trip->customer_phone = $request->customer_phone;
        $trip->lat = $request->lat;
        $trip->long = $request->long;
        $trip->drop_lat = $request->drop_lat;
        $trip->drop_long = $request->drop_long;
        $trip->save();
        $description = 'Pickup :' . $request->pickup_location;
        $description .= '<br> Delivery:' . $request->delivery_location;
        $description .= '<br> Customer Name:' . $request->customer_name;
        $description .= '<br> Customer Phone:' . $request->customer_phone;

        $stop = new Stop();
        $stop->location = $request->pickup_location;
        $stop->type = 'pickup';
        $stop->trip_id = $trip->id;
        $stop->lat = $request->lat;
        $stop->long = $request->long;
        $stop->save();

        foreach ($stops as $key => $value) {
            $description .= '<br> Stop ' . ($key + 1) . ': ' . $value['stop'];
            $stop = new Stop();
            $stop->location = $value['stop'];
            $stop->type = 'stop';
            $stop->trip_id = $trip->id;
            $stop->lat = $value['lat'];
            $stop->long = $value['lng'];
            $stop->save();
        }

        $stop = new Stop();
        $stop->location = $request->delivery_location;
        $stop->type = 'destination';
        $stop->trip_id = $trip->id;
        $stop->lat = $request->drop_lat;
        $stop->long = $request->drop_long;
        $stop->save();

        // $user = User::where('type', 'superadmin')->first();
        // $accessToken = $user->access_token;
        // $client = $this->getClient();
        // $client->setAccessToken($accessToken);
        // $service = new Google_Service_Calendar($client);
        // $pickupDateTime = new DateTime($trip->pickup_date);
        // $formattedPickupDateTime = $pickupDateTime->format('Y-m-d\TH:i:s\Z');
        // $deliveryDateTime = new DateTime($trip->delivery_date);
        // $formattedDeliveryDateTime = $deliveryDateTime->format('Y-m-d\TH:i:s\Z');

        // $event = new Google_Service_Calendar_Event([
        //     'summary' => $unique,
        //     'description' => $description,
        //     'start' => [
        //         'dateTime' => $formattedPickupDateTime,
        //         'timeZone' => 'UTC', // Adjust as needed
        //     ],
        //     'end' => [
        //         'dateTime' => $formattedDeliveryDateTime,
        //         'timeZone' => 'UTC', // Adjust as needed
        //     ],
        // ]);
        // $calendarId = 'rw.passengers@gmail.com';
        // // $calendarId = 'primary';
        // $event = $service->events->insert($calendarId, $event);
        // $event_id = $event->getId();

        // $trip->event_id = $event_id;
        $trip->save();
        return redirect('trips')->with('success', 'Trip added successfully');
    }

    public function edit($id)
    {
        $trip = Trip::where('id', $id)->with('stops')->first();
        $drivers = Driver::where('id', '>', 0)->with('user')->get();
        return view('trips.edit', compact('trip', 'drivers'));
    }

    public function update(Request $request)
    {
        $decodedJson = html_entity_decode($request->stops);
        $stops = json_decode($decodedJson, true);
        $trip = Trip::find($request->trip_id);
        $trip->user_id = $request->user_id;
        $trip->pickup_date = $request->pickup_date;
        $trip->delivery_date = $request->delivery_date;
        $trip->pickup_location = $request->pickup_location;
        $trip->delivery_location = $request->delivery_location;
        $trip->estimated_distance = $request->estimated_distance;
        $trip->estimated_time = $request->estimated_time;
        $trip->customer_name = $request->customer_name;
        $trip->customer_phone = $request->customer_phone;
        $trip->lat = $request->lat;
        $trip->long = $request->long;
        $trip->drop_lat = $request->drop_lat;
        $trip->drop_long = $request->drop_long;
        $trip->save();
        Stop::where('trip_id', $request->trip_id)->delete();

        $description = 'Pickup :' . $request->pickup_location;
        $description .= '<br> Delivery:' . $request->delivery_location;
        $description .= '<br> Customer Name:' . $request->customer_name;
        $description .= '<br> Customer Phone:' . $request->customer_phone;


        $stop = new Stop();
        $stop->location = $request->pickup_location;
        $stop->type = 'pickup';
        $stop->trip_id = $request->trip_id;
        $stop->lat = $request->lat;
        $stop->long = $request->long;
        $stop->save();


        foreach ($stops as $key => $value) {
            $description .= '<br> Stop ' . ($key + 1) . ': ' . $value['stop'];
            $stop = new Stop();
            $stop->location = $value['stop'];
            $stop->type = 'stop';
            $stop->trip_id = $request->trip_id;
            $stop->lat = $value['lat'];
            $stop->long = $value['lng'];
            $stop->save();
        }

        $stop = new Stop();
        $stop->location = $request->delivery_location;
        $stop->type = 'destination';
        $stop->trip_id = $request->trip_id;
        $stop->lat = $request->drop_lat;
        $stop->long = $request->drop_long;
        $stop->save();


        // $user = User::where('type', 'superadmin')->first();
        // $accessToken = $user->access_token;
        // $client = $this->getClient();
        // $client->setAccessToken($accessToken);
        // $service = new Google_Service_Calendar($client);
        // $pickupDateTime = new DateTime($trip->pickup_date);
        // $formattedPickupDateTime = $pickupDateTime->format('Y-m-d\TH:i:s\Z');
        // $deliveryDateTime = new DateTime($trip->delivery_date);
        // $formattedDeliveryDateTime = $pickupDateTime->format('Y-m-d\TH:i:s\Z');

        // $event = $service->events->get('rw.passengers@gmail.com', $request->event_id);
        // $event->setSummary($trip->unique_id);
        // $event->setDescription($description);
        // $event->setStart(new \Google_Service_Calendar_EventDateTime(['dateTime' => $formattedPickupDateTime]));
        // $event->setEnd(new \Google_Service_Calendar_EventDateTime(['dateTime' => $formattedDeliveryDateTime]));
        // $service->events->update('rw.passengers@gmail.com', $request->event_id, $event);


        return redirect('trips')->with('success', 'Trip updated successfully');
    }
    public function delete($id)
    {
        $trip = Trip::find($id);
        // $user = User::where('type', 'superadmin')->first();
        // $accessToken = $user->access_token;
        // $client = $this->getClient();
        // $client->setAccessToken($accessToken);
        // $service = new Google_Service_Calendar($client);
        // $service->events->delete('rw.passengers@gmail.com', $trip->event_id);
        // Stop::where('trip_id', $id)->delete();
        $trip->delete();
        return redirect('trips')->with('success', 'Trip deleted successfully');
    }
}
