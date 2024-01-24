<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;
use stdClass;
class NotificationController extends Controller
{
    public function apiJsonResponse($code, $message, $data, $error)
    {
        $response = new stdClass();
        $response->status_code = $code;
        $response->message = $message;
        $response->error = $error;
        $response->data = $data;
        return response()->json($response, $response->status_code);
    }

    public function index(Request $request)
    {
        $notifications=Notification::where('user_id',$request->user()->id)->get();
        if($notifications!=null){
            return $this->apiJsonResponse(200, "Data found!", $notifications, "");
        }
        return $this->apiJsonResponse(400, "No data found!", "", "");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function seenNotification(Request $request)
    {
        $seen=Notification::where('user_id',$request->user()->id)->update(['seen'=>1]);
        if($seen){
            return $this->apiJsonResponse(200, "Data found!", "", "");
        }
        return $this->apiJsonResponse(400, "No data found!", "", "");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
