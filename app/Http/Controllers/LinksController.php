<?php

namespace App\Http\Controllers;

use App\Models\TrackingLink;
use App\Models\Trip;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Str;

class LinksController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $links = TrackingLink::with('trips')->get();
            return DataTables::of($links)->addIndexColumn()->make(true);
        }
        $total = TrackingLink::count();
        return view('links.links', compact('total'));
    }

    public function add()
    {
        $trips = Trip::whereNotIn('status', ['completed', 'available','rejected'])->whereNotNull('status')->with('driver')->get();
        // dd($trips);
        return view('links.create', compact('trips'));
    }

    public function create(Request $request)
    {
        $slug = Str::random(40);
        $link = new TrackingLink();
        $link->name = $request->link_name;
        $link->slug = $slug;
        $link->url = url('live/track/events', $slug);
        $link->save();

        $link->trips()->sync($request->trips);

        return redirect('links')->with('success','Link registered');
    }

    public function edit($id)
    {
        $trips = Trip::whereNotIn('status', ['completed', 'available','rejected'])->whereNotNull('status')->with('driver')->get();
        $link = TrackingLink::find($id);
        return view('links.edit', compact('trips','link'));
    }


    public function update(Request $request)
    {
        // dd($request->all());
        $link = TrackingLink::find($request->link_id);
        $link->name = $request->link_name;
        $link->save();
        $link->trips()->sync($request->trips);

        return redirect('links')->with('success','Link updated');
    }

    public function delete($id)
    {
        $link = TrackingLink::find($id);
        $link->delete();
        return redirect('links')->with('success','Link deleted');
    }

}
