<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Stop;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\Notification;
use DateTime;

use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;
use Illuminate\Support\Facades\Auth;
use App\Services\NotificationService;
use Str;

class TripsController extends Controller
{
    private $url;

    // public function __construct(Request $r)
    // {
    //     // dd($request->url());
    //     $this->url = $r->url();
    //     // $this->middleware(function ($request, $next) {
    //     //     $this->client = new Google_Client();
    //     //     $this->client->setAuthConfig(public_path('/credentials.json'));
    //     //     $this->client->setRedirectUri(url('google/auth/callback'));
    //     //     $this->client->addScope(Google_Service_Calendar::CALENDAR);
    //     //     return $next($request);
    //     // });
    // }


    public function showAuthorizationForm()
    {
        $client = $this->getClient();
        $authUrl = $client->createAuthUrl();

        return view('google.auth', compact('authUrl'));
    }


    private function getClient()
    {
        $client = new Google_Client();
        $client->setAuthConfig(public_path('/credentials.json')); // Path to your credentials file
        $client->setRedirectUri(url('google/auth/callback'));
        $client->setScopes([
            Google_Service_Calendar::CALENDAR,
        ]);

        return $client;
    }

    public function handleAuthorizationCallback(Request $request)
    {
        $client = $this->getClient();
        // dd($request->url);
        $accessToken = $client->fetchAccessTokenWithAuthCode($request->get('code'));
        $user = User::where('type', 'superadmin')->first();
        // dd($accessToken);
        $user->access_token = $accessToken['access_token'];
        $user->save();
        return redirect('/trips');
    }

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

    public function available(Request $request)
    {
        if (Auth::user()->type == "superadmin") {
            $user = User::where('type', 'superadmin')->first();
            $accessToken = $user->access_token;
            $client = $this->getClient();
            $client->setAccessToken($accessToken);
            $service = new Google_Service_Calendar($client);
        }
        if ($request->ajax()) {
            if (Auth::user()->type == "superadmin") {

                $trips = Trip::where('id', '>', 0)->get();
                $calendarId = 'rw.passengers@gmail.com';
                foreach ($trips as $key => $value) {
                    if ($value->event_id != null) {
                        $eventId = $value->event_id;
                        $event = $service->events->get($calendarId, $eventId);
                        // dd("HI");
                        $startTime = $event->start->dateTime;
                        $endTime = $event->end->dateTime;
                        $startTime = str_replace('T', " ", $startTime);
                        $startTime = str_replace('Z', "", $startTime);
                        $endTime = str_replace('T', " ", $endTime);
                        $endTime = str_replace('Z', "", $endTime);

                        $event_name = $event->getSummary();
                        $description = $event->getDescription();
                        if ($startTime != $value->pickup_date || $endTime != $value->delivery_date || $description != $value->description || $event_name != $value->event_name) {
                            Trip::where('id', $value->id)->update(['pickup_date' => $startTime, 'delivery_date' => $endTime, 'description' => $description, 'event_name' => $event_name]);
                        }
                    }
                }
            }
            $trips = Trip::latest()->where('status', 'available')->orWhereNull('status')->with('stops', 'driver')->get();
            return DataTables::of($trips)->make(true);
        }
        if (Auth::user()->type == "superadmin") {

            try {
                $events = $service->events->listEvents('rw.passengers@gmail.com');
                // dd($events);
                // dd($events->getItems(3)[0]->getId());
                foreach ($events->getItems() as $key => $event) {
                    $trip = Trip::where('event_id', $event->getId())->first();
                    if ($trip == null) {
                        $last = Trip::latest()->first();
                        if ($last == null) {
                            $unique = "GO-00001";
                        } else {
                            $last = $last->unique_id;
                            $numericPart = substr($last, 3);
                            $nextNumericPart = str_pad((int)$numericPart + 1, strlen($numericPart), '0', STR_PAD_LEFT);
                            $unique = 'GO-' . $nextNumericPart;
                        }
                        $startTime = str_replace('T', " ", $event->getStart()->getDateTime());
                        $startTime = str_replace('Z', "", $startTime);
                        $endTime = str_replace('T', " ", $event->getEnd()->getDateTime());
                        $endTime = str_replace('Z', "", $endTime);
                        $new = new Trip();
                        $new->unique_id = $unique;
                        $new->event_id = $event->getId();
                        $new->pickup_date = $startTime;
                        $new->delivery_date = $endTime;
                        $new->event_name = $event->getSummary();
                        $new->description = $event->getDescription();
                        $new->save();
                    }
                }


                $trips = Trip::where('status', 'available')->whereNull('event_id')->get();
                foreach ($trips as &$trip) {
                    $pickupDateTime = new DateTime($trip->pickup_date);
                    $formattedPickupDateTime = $pickupDateTime->format('Y-m-d\TH:i:s\Z');
                    $deliveryDateTime = new DateTime($trip->delivery_date);
                    $formattedDeliveryDateTime = $deliveryDateTime->format('Y-m-d\TH:i:s\Z');

                    $event = new Google_Service_Calendar_Event([
                        'summary' => $request->event_name,
                        'description' => $trip->description,
                        'start' => [
                            'dateTime' => $formattedPickupDateTime,
                            'timeZone' => 'UTC', // Adjust as needed
                        ],
                        'end' => [
                            'dateTime' => $formattedDeliveryDateTime,
                            'timeZone' => 'UTC', // Adjust as needed
                        ],
                    ]);
                    $calendarId = 'rw.passengers@gmail.com';
                    // $calendarId = 'primary';
                    $event = $service->events->insert($calendarId, $event);
                    $trip->event_id  = $event->getId();
                    $trip->save();
                }
            } catch (\Exception $e) {
                // dd($e->getMessage());
                $error = json_decode($e->getMessage(), true);
                if ($error['error']['code'] == 401) {
                    $url  = $client->createAuthUrl();
                    return redirect($url);
                    exit;
                }
                return redirect()->back();
            }
        }
        $data['available'] = Trip::whereIn('status', ['available'])->count();
        $data['incomplete'] = Trip::whereNull('status')->count();
        return view('trips.trips', compact('data'));
    }

    public function active(Request $request,$status='all')
    {
        if (Auth::user()->type == "superadmin") {

            $user = User::where('type', 'superadmin')->first();
            $accessToken = $user->access_token;
            $client = $this->getClient();
            $client->setAccessToken($accessToken);
            $service = new Google_Service_Calendar($client);
        }
        if ($request->ajax()) {
            if (Auth::user()->type == "superadmin") {

                $trips = Trip::where('id', '>', 0)->get();
                $calendarId = 'rw.passengers@gmail.com';
                foreach ($trips as $key => $value) {
                    if ($value->event_id != null) {

                        $eventId = $value->event_id;
                        $event = $service->events->get($calendarId, $eventId);
                        // dd("HI");
                        $startTime = $event->start->dateTime;
                        $endTime = $event->end->dateTime;
                        $startTime = str_replace('T', " ", $startTime);
                        $startTime = str_replace('Z', "", $startTime);
                        $endTime = str_replace('T', " ", $endTime);
                        $endTime = str_replace('Z', "", $endTime);

                        $event_name = $event->getSummary();
                        $description = $event->getDescription();
                        if ($startTime != $value->pickup_date || $endTime != $value->delivery_date || $description != $value->description || $event_name != $value->event_name) {
                            Trip::where('id', $value->id)->update(['pickup_date' => $startTime, 'delivery_date' => $endTime, 'description' => $description, 'event_name' => $event_name]);
                        }
                    }
                }
            }
            if($status=='all'){
                $trips = Trip::latest()->whereNotIn('status', ['available', 'completed'])->whereNotNull('status')->with('stops', 'driver')->get();
            }else if($status=='pick'){
                $trips = Trip::latest()->where('status', 'pickup')->with('stops', 'driver')->get();
            }else if($status=='drop'){
                $trips = Trip::latest()->where('status', 'destination')->with('stops', 'driver')->get();
            }else if($status=='intransit'){
                $trips = Trip::latest()->where('status', 'in-transit')->with('stops', 'driver')->get();
            }

            return DataTables::of($trips)->make(true);
        }
        if (Auth::user()->type == "superadmin") {

            try {
                $events = $service->events->listEvents('rw.passengers@gmail.com');
                // dd($events->getItems(3)[0]->getId());
            } catch (\Exception $e) {
                $error = json_decode($e->getMessage(), true);
                // dd($e->getMessage());
                if ($error['error']['code'] == 401) {
                    $url  = $client->createAuthUrl();
                    return redirect($url);
                    exit;
                }
            }
        }
        $total = Trip::whereNotIn('status', ['available', 'completed'])->whereNotNull('status')->count();
        return view('trips.active', compact('total'));
    }

    public function completed(Request $request)
    {
        if (Auth::user()->type == "superadmin") {

            $user = User::where('type', 'superadmin')->first();
            $accessToken = $user->access_token;
            $client = $this->getClient();
            $client->setAccessToken($accessToken);
            $service = new Google_Service_Calendar($client);
        }
        if ($request->ajax()) {
            if (Auth::user()->type == "superadmin") {

                $trips = Trip::where('id', '>', 0)->get();
                $calendarId = 'rw.passengers@gmail.com';
                foreach ($trips as $key => $value) {
                    if ($value->event_id != null) {

                        $eventId = $value->event_id;
                        $event = $service->events->get($calendarId, $eventId);
                        // dd("HI");
                        $startTime = $event->start->dateTime;
                        $endTime = $event->end->dateTime;
                        $startTime = str_replace('T', " ", $startTime);
                        $startTime = str_replace('Z', "", $startTime);
                        $endTime = str_replace('T', " ", $endTime);
                        $endTime = str_replace('Z', "", $endTime);

                        $event_name = $event->getSummary();
                        $description = $event->getDescription();
                        if ($startTime != $value->pickup_date || $endTime != $value->delivery_date || $description != $value->description || $event_name != $value->event_name) {
                            Trip::where('id', $value->id)->update(['pickup_date' => $startTime, 'delivery_date' => $endTime, 'description' => $description, 'event_name' => $event_name]);
                        }
                    }
                }
            }
            $trips = Trip::latest()->where('status', 'completed')->with('stops', 'driver')->get();
            return DataTables::of($trips)->make(true);
        }
        if (Auth::user()->type == "superadmin") {

            try {
                $events = $service->events->listEvents('rw.passengers@gmail.com');
            } catch (\Exception $e) {
                $error = json_decode($e->getMessage(), true);
                // dd($e->getMessage());
                if ($error['error']['code'] == 401) {
                    $url  = $client->createAuthUrl();
                    return redirect($url);
                    exit;
                }
            }
        }
        $total = Trip::where('status', 'completed')->count();
        return view('trips.completed', compact('total'));
    }



    public function new()
    {
        if (Auth::user()->type == "superadmin") {

            try {
                $user = User::where('type', 'superadmin')->first();
                $accessToken = $user->access_token;
                $client = $this->getClient();
                $client->setAccessToken($accessToken);
                $service = new Google_Service_Calendar($client);
                $events = $service->events->listEvents('rw.passengers@gmail.com');
            } catch (\Exception $e) {
                $error = json_decode($e->getMessage(), true);
                // dd($e->getMessage());
                if ($error['error']['code'] == 401) {
                    $url  = $client->createAuthUrl();
                    return redirect($url);
                    exit;
                }
            }
        }
        $drivers = Driver::where('id', '>', 0)->with('user')->get();
        return view('trips.create', compact('drivers'));
    }

    public function create(Request $request)
    {
        // dd($request->all());
        $decodedJson = html_entity_decode($request->stops);
        $stops = json_decode($decodedJson, true);
        $stops_descriptions = json_decode($request->stop_descriptions, true);

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
        $randomSlug = Str::random(8);
        $uniqueRandomSlug = $randomSlug . '_' . time();
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
        $trip->event_name = $request->event_name;
        $trip->description = $request->description;
        $trip->slug = $uniqueRandomSlug;
        $trip->status = 'available';
        $trip->save();
        if ($request->user_id != null || $request->user_id != "") {
            $data = [
                'message' => 'You havs assigned a new trip!',
                'title' => 'New trip',
                'sound' => 'newtrip.mp3',
            ];
            $this->sendDriverNotification($request->user_id, $data);
        }

        $description = $request->description;

        $stop = new Stop();
        $stop->location = $request->pickup_location;
        $stop->type = 'pickup';
        $stop->trip_id = $trip->id;
        $stop->lat = $request->lat;
        $stop->long = $request->long;
        $stop->description = $request->start_description;
        $stop->save();

        foreach ($stops as $key => $value) {
            $stop = new Stop();
            $stop->location = $value['stop'];
            $stop->type = 'stop';
            $stop->trip_id = $trip->id;
            $stop->lat = $value['lat'];
            $stop->long = $value['lng'];
            $stop->description = $stops_descriptions[$key];
            $stop->save();
        }

        $stop = new Stop();
        $stop->location = $request->delivery_location;
        $stop->type = 'destination';
        $stop->trip_id = $trip->id;
        $stop->lat = $request->drop_lat;
        $stop->long = $request->drop_long;
        $stop->description = $request->end_description;
        $stop->save();

        $event_id = null;
        if (Auth::user()->type == "superadmin") {

            $user = User::where('type', 'superadmin')->first();
            $accessToken = $user->access_token;
            $client = $this->getClient();
            $client->setAccessToken($accessToken);
            $service = new Google_Service_Calendar($client);
            $pickupDateTime = new DateTime($trip->pickup_date);
            $formattedPickupDateTime = $pickupDateTime->format('Y-m-d\TH:i:s\Z');
            $deliveryDateTime = new DateTime($trip->delivery_date);
            $formattedDeliveryDateTime = $deliveryDateTime->format('Y-m-d\TH:i:s\Z');

            $event = new Google_Service_Calendar_Event([
                'summary' => $request->event_name,
                'description' => $description,
                'start' => [
                    'dateTime' => $formattedPickupDateTime,
                    'timeZone' => 'UTC', // Adjust as needed
                ],
                'end' => [
                    'dateTime' => $formattedDeliveryDateTime,
                    'timeZone' => 'UTC', // Adjust as needed
                ],
            ]);
            $calendarId = 'rw.passengers@gmail.com';
            // $calendarId = 'primary';
            $event = $service->events->insert($calendarId, $event);
            $event_id = $event->getId();
        }
        $trip->event_id = $event_id;
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
        // dd($request->all());
        $decodedJson = html_entity_decode($request->stops);
        $stops = json_decode($decodedJson, true);
        $stops_descriptions = json_decode($request->stop_descriptions, true);

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
        $trip->event_name = $request->event_name;
        $trip->description = $request->description;
        if ($trip->status == null) {
            $trip->status = 'available';
        }
        $trip->save();
        if ($request->user_id != null || $request->user_id != "") {
            $data = [
                'message' => 'Your trip is updated. See details!',
                'title' => 'Trip updated',
                'sound' => 'anychange.mp3',
            ];
            $this->sendDriverNotification($request->user_id, $data);
        }
        Stop::where('trip_id', $request->trip_id)->delete();

        $description = $request->description;


        $stop = new Stop();
        $stop->location = $request->pickup_location;
        $stop->type = 'pickup';
        $stop->trip_id = $request->trip_id;
        $stop->lat = $request->lat;
        $stop->long = $request->long;
        $stop->description = $request->start_description;
        $stop->save();


        foreach ($stops as $key => $value) {
            $stop = new Stop();
            $stop->location = $value['stop'];
            $stop->type = 'stop';
            $stop->trip_id = $request->trip_id;
            $stop->lat = $value['lat'];
            $stop->long = $value['lng'];
            $stop->description = $stops_descriptions[$key];
            $stop->save();
        }

        $stop = new Stop();
        $stop->location = $request->delivery_location;
        $stop->type = 'destination';
        $stop->trip_id = $request->trip_id;
        $stop->lat = $request->drop_lat;
        $stop->long = $request->drop_long;
        $stop->description = $request->end_description;
        $stop->save();

        if (Auth::user()->type == "superadmin") {


            $user = User::where('type', 'superadmin')->first();
            $accessToken = $user->access_token;
            $client = $this->getClient();
            $client->setAccessToken($accessToken);
            $service = new Google_Service_Calendar($client);
            $pickupDateTime = new DateTime($trip->pickup_date);
            $formattedPickupDateTime = $pickupDateTime->format('Y-m-d\TH:i:s\Z');
            $deliveryDateTime = new DateTime($trip->delivery_date);
            $formattedDeliveryDateTime = $deliveryDateTime->format('Y-m-d\TH:i:s\Z');

            $event = $service->events->get('rw.passengers@gmail.com', $request->event_id);
            $event->setSummary($trip->event_name);
            $event->setDescription($description);
            $event->setStart(new \Google_Service_Calendar_EventDateTime(['dateTime' => $formattedPickupDateTime]));
            $event->setEnd(new \Google_Service_Calendar_EventDateTime(['dateTime' => $formattedDeliveryDateTime]));
            $service->events->update('rw.passengers@gmail.com', $request->event_id, $event);
        }
        return redirect('trips')->with('success', 'Trip updated successfully');
    }
    public function delete($id)
    {
        $trip = Trip::find($id);
        if (Auth::user()->type == "superadmin") {

            $user = User::where('type', 'superadmin')->first();
            $accessToken = $user->access_token;
            $client = $this->getClient();
            $client->setAccessToken($accessToken);
            $service = new Google_Service_Calendar($client);
            $service->events->delete('rw.passengers@gmail.com', $trip->event_id);
        }
        Stop::where('trip_id', $id)->delete();
        $trip->delete();
        return redirect('trips')->with('success', 'Trip deleted successfully');
    }
    private function sendDriverNotification($id, $data)
    {
        $user = User::where('id', $id)->first();
        if ($user->fcm_token != null) {
            (new NotificationService)->sendNotification($user->fcm_token, $data, 'admin');
        }
        Notification::create([
            'title' => $data['title'],
            'notification' => $data['message'],
            'type' => 'notification',
            'user_id' => $id,
            'seen' => 0,
        ]);
    }
}
