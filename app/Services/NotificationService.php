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

class NotificationService
{
    public function sendNotification($fcm_token, $notification_data,$user){
       if($user=='driver'){
            $data = [
                "to" => $fcm_token,
                "data"=>[
                    
                        "title" => $notification_data['title'],
                        "body" => $notification_data['message'],
                        'icon'=>'', 
                        'sound' => 'default',
                        'priority' => 'high',
                        
                ],
                "delay_while_idle" => false, 
                "priority" => "high", 
                "content_available" => true 
                
            ];
       }else{
    
            $data = [
                "to" => 'IvQ3lREUt8KtQY7dSdxpGz0a1hmOM6eMUr_kvpyyQc0bmHSrPeSwVqEuBHOhq48tN2PDfBj5BVCa2jPcA5sP8CBn6FRRhp37ieOUDIIoJal2E4U5I',
                "data"=>[
                    
                        "title" => $notification_data['title'],
                        "body" => $notification_data['message'],
                        'icon'=>'', 
                        'sound' => 'notificationfromplatform',
                        'priority' => 'high',
                        
                ],
                "delay_while_idle" => false, 
                "priority" => "high", 
                "content_available" => true 
                
            ];
       }
        
        $SERVER_API_KEY=env('FIREBASE_SERVER_API_KEY');
        $dataString = json_encode($data);
        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

        $response = curl_exec($ch);
        if ($response === false) {
            $error = curl_error($ch);
        }
        $responseData = json_decode($response, true);
        return $response;
    }
    
}
?>