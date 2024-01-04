<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Stop;
use App\Models\Trip;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class TripsController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $trips = Trip::where('id','>',0)->with('stops','driver')->get();
            return DataTables::of($trips)->make(true);
        }
        $total = Trip::count();
        return view('trips.trips', compact('total'));
    }

    // public function trips($from, $to)
    // {
        // dd($from);
    //     $trips = Trip::where('id', '>', 0)
    //         ->where('service_in', '>=', $from)
    //         ->where('service_in', '<=', $to . " 23:59:59")
    //         ->with('driver')
    //         ->orderBy('created_at', 'DESC')->get();
    //     return DataTables::of($trips)->make(true);
    // }

    public function new()
    {
        $drivers = Driver::where('id', '>', 0)->with('user')->get();
        // dd($drivers);
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
        foreach($stops as $value){
            $stop = new Stop();
            $stop->location = $value['stop'];
            $stop->trip_id = $trip->id;
            $stop->lat = $value['lat'];
            $stop->long = $value['lng'];
            $stop->save();
        }
        return redirect('trips')->with('success','Trip added successfully');
    }

    public function delete($id){
        Trip::find($id)->delete();
        Stop::where('trip_id',$id)->delete();
        return redirect('trips')->with('success','Trip deleted successfully');
    }
}
