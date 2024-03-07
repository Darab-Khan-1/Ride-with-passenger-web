<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Stop;
use App\Models\Attribute;
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
        if (isset($accessToken['access_token'])) {
            $user->access_token = $accessToken['access_token'];
            $user->save();
        }
        // dd("hi");
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
        // dd(session('allowed_by_google'));
        if (Auth::user()->type == "superadmin" && (session('allowed_by_google') != null && session('allowed_by_google') > 0)) {
            $user = User::where('type', 'superadmin')->first();
            $accessToken = $user->access_token;
            $client = $this->getClient();
            $client->setAccessToken($accessToken);
            $service = new Google_Service_Calendar($client);
        }
        if ($request->ajax()) {
            if (Auth::user()->type == "superadmin" && (session('allowed_by_google') != null && session('allowed_by_google') > 0)) {

                $now = new DateTime('now');
                $yesterday = $now->modify('-1 day')->format('Y-m-d');
                $nextWeek = $now->modify('+3 days')->format('Y-m-d');
                $trips = Trip::whereBetween('pickup_date', [$yesterday, $nextWeek])->get();
                // dd(count($trips));
                $calendarId = 'rw.passengers@gmail.com';

                // $batchRequests = [];

                // foreach ($trips as $trip) {
                //     if ($trip->event_id !== null) {
                //         // Add batch request to fetch event details for the trip
                //         $batchRequests[$trip->id] = $service->events->get($calendarId, $trip->event_id);
                //     }
                // }

                // // Execute batch requests
                // $batchResults = $service->events->executeBatch($batchRequests);

                // foreach ($trips as $trip) {
                //     if (isset($batchResults[$trip->id])) {
                //         $event = $batchResults[$trip->id];
                //         // Extract event details
                //         $startTime = $event->start->dateTime;
                //         $endTime = $event->end->dateTime;
                //         $startTime = str_replace('T', " ", $startTime);
                //         $startTime = str_replace('Z', "", $startTime);
                //         $endTime = str_replace('T', " ", $endTime);
                //         $endTime = str_replace('Z', "", $endTime);

                //         $event_name = $event->getSummary();
                //         $description = $event->getDescription();

                //         // Check if trip details need to be updated
                //         if ($startTime !== $trip->pickup_date || $endTime !== $trip->delivery_date || $description !== $trip->description || $event_name !== $trip->event_name) {
                //             // Update trip details
                //             $trip->pickup_date = ($startTime === "1970-01-01 00:00:00" ? now() : date('Y-m-d H:i:s', strtotime($startTime)));
                //             $trip->delivery_date = ($endTime === "1970-01-01 00:00:00" ? now() : date('Y-m-d H:i:s', strtotime($endTime)));
                //             $trip->description = $description;
                //             $trip->event_name = $event_name;
                //             $trip->save();
                //         }
                //     }
                // }
                foreach ($trips as $key => &$value) {
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
                            $startTime = (date('Y-m-d H:i:s', strtotime($startTime)) == "1970-01-01 00:00:00" ? date('Y-m-d H:i:s', strtotime('now')) : date('Y-m-d H:i:s', strtotime($startTime)));
                            $endTime = (date('Y-m-d H:i:s', strtotime($endTime)) == "1970-01-01 00:00:00" ? date('Y-m-d H:i:s', strtotime('now')) : date('Y-m-d H:i:s', strtotime($endTime)));
                            $value->pickup_date = $startTime;
                            $value->delivery_date = $endTime;
                            $value->description = $description;
                            $value->event_name = $event_name;
                            $value->save();
                        }
                    }
                }
            }


            $trips = Trip::where('status', 'available')->orWhereNull('status')->with('stops', 'driver')
                ->orderby('pickup_date', 'asc')
                ->get();
            // dd($trips);
            // {{ dd($trips->trips->id) }}
            return DataTables::of($trips)->make(true);
        }
        if (Auth::user()->type == "superadmin" && (session('allowed_by_google') != null && session('allowed_by_google') > 0)) {

            try {
                // $events = $service->events->listEvents('rw.passengers@gmail.com');
                // dd($events);
                // dd($events->getItems(3)[0]->getId());
                $events = [];
                $pageToken = NULL;
                $now = new DateTime('now');
                $yesterday = $now->modify('-1 day')->format('Y-m-d');
                $nextWeek = $now->modify('+3 days')->format('Y-m-d');

                do {
                    $calendarEvents = $service->events->listEvents('rw.passengers@gmail.com', [
                        'timeMin' => $yesterday . 'T00:00:00Z', // Events starting from yesterday
                        'timeMax' => $nextWeek . 'T23:59:59Z',   // Events until next 7 days
                        'pageToken' => $pageToken
                    ]);

                    $events = array_merge($events, $calendarEvents->getItems());
                    $pageToken = $calendarEvents->getNextPageToken();
                } while ($pageToken);


                foreach ($events as $key => $event) {
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
                        $new->pickup_date = (date('Y-m-d H:i:s', strtotime($startTime)) == "1970-01-01 00:00:00" ? date('Y-m-d H:i:s', strtotime('now')) : date('Y-m-d H:i:s', strtotime($startTime)));
                        $new->delivery_date = (date('Y-m-d H:i:s', strtotime($endTime)) == "1970-01-01 00:00:00" ? date('Y-m-d H:i:s', strtotime('now')) : date('Y-m-d H:i:s', strtotime($endTime)));
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
                    session(['allowed_by_google' => 3]);
                }
            } catch (\Exception $e) {
                $error = json_decode($e->getMessage(), true);
                // dd($e);
                if ($error['error']['code'] == 401) {
                    $url  = $client->createAuthUrl();
                    return redirect($url);
                    exit;
                } elseif ($error['error']['code'] == 404) {
                    if (session('allowed_by_google') > 0) {
                        $val = session('allowed_by_google');
                        $val--;
                        session(['allowed_by_google' => $val]);
                        // dd($val);
                        // dd(session('allowed_by_google'));
                        $url  = $client->createAuthUrl();
                        return redirect($url);
                    }
                    // session(['allowed_by_google' => false]);
                }
                return redirect()->back();
            }
        }
        $data['available'] = Trip::whereIn('status', ['available'])->count();
        $data['incomplete'] = Trip::whereNull('status')->count();
        return view('trips.trips', compact('data'));
    }

    public function active(Request $request, $status = 'all')
    {
        if (Auth::user()->type == "superadmin" && (session('allowed_by_google') != null && session('allowed_by_google') > 0)) {

            $user = User::where('type', 'superadmin')->first();
            $accessToken = $user->access_token;
            $client = $this->getClient();
            $client->setAccessToken($accessToken);
            $service = new Google_Service_Calendar($client);
        }
        if ($request->ajax()) {
            if (Auth::user()->type == "superadmin" && (session('allowed_by_google') != null && session('allowed_by_google') > 0)) {

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
            if ($status == 'all') {
                $trips = Trip::whereNotIn('status', ['available', 'completed'])->whereNotNull('status')->with('stops', 'driver')->orderby('pickup_date', 'asc')->get();
            } else if ($status == 'pick') {
                $trips = Trip::where('status', 'pickup')->with('stops', 'driver')->orderby('pickup_date', 'asc')->get();
            } else if ($status == 'drop') {
                $trips = Trip::where('status', 'destination')->with('stops', 'driver')->orderby('pickup_date', 'asc')->get();
            } else if ($status == 'intransit') {
                $trips = Trip::where('status', 'in-transit')->with('stops', 'driver')->orderby('pickup_date', 'asc')->get();
            }

            return DataTables::of($trips)->make(true);
        }
        if (Auth::user()->type == "superadmin" && (session('allowed_by_google') != null && session('allowed_by_google') > 0)) {

            try {
                $events = $service->events->listEvents('rw.passengers@gmail.com');
                // dd($events->getItems(3)[0]->getId());
                session(['allowed_by_google' => 3]);
            } catch (\Exception $e) {
                $error = json_decode($e->getMessage(), true);
                // dd($e->getMessage());
                if ($error['error']['code'] == 401) {
                    $url  = $client->createAuthUrl();
                    return redirect($url);
                    exit;
                } elseif ($error['error']['code'] == 404) {
                    // dd($error);
                    if (session('allowed_by_google' != 0)) {
                        $val = session('allowed_by_google');
                        $val--;
                        session(['allowed_by_google' => $val]);
                        $url  = $client->createAuthUrl();
                        return redirect($url);
                    }
                    // session(['allowed_by_google' => false]);
                }
            }
        }
        $total = Trip::whereNotIn('status', ['available', 'completed'])->whereNotNull('status')->count();

        return view('trips.active', compact('total'));
    }

    public function completed(Request $request)
    {
        if (Auth::user()->type == "superadmin" && (session('allowed_by_google') != null && session('allowed_by_google') > 0)) {

            $user = User::where('type', 'superadmin')->first();
            $accessToken = $user->access_token;
            $client = $this->getClient();
            $client->setAccessToken($accessToken);
            $service = new Google_Service_Calendar($client);
        }
        if ($request->ajax()) {
            if (Auth::user()->type == "superadmin" && (session('allowed_by_google') != null && session('allowed_by_google') > 0)) {

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
            $trips = Trip::where('status', 'completed')->with('stops', 'driver')->orderby('updated_at', 'desc')->get();
            return DataTables::of($trips)->make(true);
        }
        if (Auth::user()->type == "superadmin" && (session('allowed_by_google') != null && session('allowed_by_google') > 0)) {

            try {
                $events = $service->events->listEvents('rw.passengers@gmail.com');
                session(['allowed_by_google' => 3]);
            } catch (\Exception $e) {
                $error = json_decode($e->getMessage(), true);
                // dd($e->getMessage());
                if ($error['error']['code'] == 401) {
                    $url  = $client->createAuthUrl();
                    return redirect($url);
                    exit;
                } elseif ($error['error']['code'] == 404) {
                    // dd($error);
                    if (session('allowed_by_google' != 0)) {
                        $val = session('allowed_by_google');
                        $val--;
                        session(['allowed_by_google' => $val]);
                        $url  = $client->createAuthUrl();
                        return redirect($url);
                    }
                    // session(['allowed_by_google' => false]);
                }
            }
        }
        $total = Trip::where('status', 'completed')->count();
        return view('trips.completed', compact('total'));
    }



    public function new()
    {
        if (Auth::user()->type == "superadmin" && (session('allowed_by_google') != null && session('allowed_by_google') > 0)) {

            try {
                $user = User::where('type', 'superadmin')->first();
                $accessToken = $user->access_token;
                $client = $this->getClient();
                $client->setAccessToken($accessToken);
                $service = new Google_Service_Calendar($client);
                $events = $service->events->listEvents('rw.passengers@gmail.com');
                session(['allowed_by_google' => 3]);
            } catch (\Exception $e) {
                $error = json_decode($e->getMessage(), true);
                // dd($e->getMessage());
                if ($error['error']['code'] == 401) {
                    $url  = $client->createAuthUrl();
                    return redirect($url);
                    exit;
                } elseif ($error['error']['code'] == 404) {
                    // dd($error);
                    if (session('allowed_by_google' != 0)) {
                        $val = session('allowed_by_google');
                        $val--;
                        session(['allowed_by_google' => $val]);
                        $url  = $client->createAuthUrl();
                        return redirect($url);
                    }
                    // session(['allowed_by_google' => false]);
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
                'message' => 'You have assigned a new trip!',
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
        if (Auth::user()->type == "superadmin" && (session('allowed_by_google') != null && session('allowed_by_google') > 0)) {

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


        $trip_field_name = $request->input('name');
        $trip_field_value = $request->input('value');
        $driverValues = $request->input('driver_value');

        // dd($driverValues);
        if($trip_field_name != null) {
            foreach ($driverValues as $key => $name) {
                $attribute = new Attribute();
                $attribute->value = $trip_field_value[$key];
                $attribute->type = "trip";
                $attribute->name = $trip_field_name[$key];
                $attribute->refrence_id = $trip->id;
                $attribute->visible_to_driver = $driverValues[$key];
                $attribute->save();
            }
        }

        return redirect('trips')->with('success', __('messages.Trip_added_successfully'));
    }

    public function edit($id)
    {
        $trip = Trip::where('id', $id)->with('stops', 'driver', 'attributes')->first();
        $attributes = Attribute::where('refrence_id', $id)->get();
        $drivers = Driver::where('id', '>', 0)->with('user')->get();
        // dd($trip);
        if ($trip->status == 'available' || $trip->status == null) {
            return view('trips.edit', compact('trip', 'drivers', 'attributes'));
        }
        // dd($trip);
        return view('trips.edit_active', compact('trip'));
    }

    public function duplicate($id)
    {
        // dd($id);
        $trip = Trip::with('stops', 'driver')->first();
        $attributes = Attribute::where('refrence_id', $id)->get();
        $drivers = Driver::where('id', '>', 0)->with('user')->get();
        return view('trips.duplicatetrip', compact('trip', 'drivers', 'attributes'));
    }

    public function update(Request $request)
    {
        // dd($request->all());
        $decodedJson = html_entity_decode($request->stops);
        $stops = json_decode($decodedJson, true);
        $stops_descriptions = json_decode($request->stop_descriptions, true);
        // dd($request->trip_id);
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
        $randomSlug = Str::random(8);
        $uniqueRandomSlug = $randomSlug . '_' . time();
        $trip->slug = $trip->slug == null ? $uniqueRandomSlug : $trip->slug;
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

        if (Auth::user()->type == "superadmin" && (session('allowed_by_google') != null && session('allowed_by_google') > 0)) {


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


        $trip_field_name = $request->input('name');
        $trip_field_value = $request->input('value');
        $driverValues = $request->input('drivers');
        $trip_ids = $request->input('attribute_id');
        // dd($request->input('attribute_id'));
        Attribute::where('refrence_id', $request->trip_id)
        ->whereNotIn('id', $trip_ids ?? [])
        ->delete();

        // Check if there are any attribute IDs
        if ($trip_ids != null) {
            foreach ($trip_ids as $key => $id) {
                $attribute = Attribute::find($id);
        
                if (!$attribute) {
                    $attribute = new Attribute();
                    $attribute->refrence_id = $request->trip_id;
                }
        
                $attribute->value = $trip_field_value[$key] ?? null;
                $attribute->name = $trip_field_name[$key] ?? null;
                $attribute->visible_to_driver = $driverValues[$key];
        
                $attribute->save();
                Attribute::where('refrence_id', $request->trip_id)
                    ->whereNotIn('id', $trip_ids)
                    ->delete();
            }
        }

        return redirect('trips')->with('success', __('messages.Trip_updated_successfully'));
    }

    public function activeUpdate(Request $request)
    {
        // dd($request->all());
        $decodedJson = html_entity_decode($request->stops);
        $stops = json_decode($decodedJson, true);
        $stops_descriptions = json_decode($request->stop_descriptions, true);

        $trip = Trip::find($request->trip_id);
        // $trip->user_id = $request->user_id;
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
        // if ($trip->status == null) {
        //     $trip->status = 'available';
        // }
        $trip->save();
        if ($request->user_id != null || $request->user_id != "") {
            $data = [
                'message' => 'Your trip is updated. See details!',
                'title' => 'Trip updated',
                'sound' => 'anychange.mp3',
            ];
            $this->sendDriverNotification($request->user_id, $data);
        }
        Stop::where('trip_id', $request->trip_id)->whereNull('datetime')->delete();
        $previous_stops = Stop::where('trip_id', $request->trip_id)->get();

        $description = $request->description;


        if (count($previous_stops) == 0) {

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
        } else {
            // foreach ($previous_stops as $p) {
            foreach ($stops as $key => $s) {
                // if($s['stop'] != $p->location){
                if (!isset($previous_stops[$key + 1])) {
                    $stop = new Stop();
                    $stop->location =  $s['stop'];
                    $stop->type =  'stop';
                    $stop->trip_id = $request->trip_id;
                    $stop->lat = $s['lat'];
                    $stop->long = $s['lng'];
                    $stop->description = $stops_descriptions[$key];
                    $stop->save();
                }
            }
            // }
            if ($trip->status != 'destination') {
                $stop = new Stop();
                $stop->location = $request->delivery_location;
                $stop->type = 'destination';
                $stop->trip_id = $request->trip_id;
                $stop->lat = $request->drop_lat;
                $stop->long = $request->drop_long;
                $stop->description = $request->end_description;
                $stop->save();
            }
        }


        if (Auth::user()->type == "superadmin" && (session('allowed_by_google') != null && session('allowed_by_google') > 0)) {


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
        return redirect('active/trips/')->with('success', 'Trip updated successfully');
    }

    public function delete($id)
    {
        $trip = Trip::find($id);
        if (Auth::user()->type == "superadmin" && (session('allowed_by_google') != null && session('allowed_by_google') > 0)) {

            $user = User::where('type', 'superadmin')->first();
            $accessToken = $user->access_token;
            $client = $this->getClient();
            $client->setAccessToken($accessToken);
            $service = new Google_Service_Calendar($client);
            $service->events->delete('rw.passengers@gmail.com', $trip->event_id);
        }
        Stop::where('trip_id', $id)->delete();
        $trip->delete();
        return redirect('trips')->with('success', __('messages.Trip_deleted_successfully'));
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
