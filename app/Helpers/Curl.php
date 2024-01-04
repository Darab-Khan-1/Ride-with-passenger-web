<?php

namespace App\Helpers;

use \stdClass;
use Config;
use DateTimeZone;
use GuzzleHttp\Client;

trait Curl
{


    public static function curl($task, $method, $cookie, $data, $header)
    {
        $res = new stdClass();
        $res->responseCode = '';
        $res->error = '';
        $header[] = "Cookie: " . $cookie;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, Config::get('constants.Constants.host') . $task);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

        if ($method == 'POST' || $method == 'PUT' || $method == 'DELETE') {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        $data = curl_exec($ch);

        $size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);

        if (preg_match('/^Set-Cookie:\s*([^;]*)/mi', substr($data, 0, $size), $c) == 1) session(['cookie' => $c[1]]);
        $res->response = substr($data, $size);

        if (!curl_errno($ch)) {
            $res->responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        } else {
            $res->responseCode = 400;
            $res->error = curl_error($ch);
        }

        curl_close($ch);
        return $res;
    }

    // public static function reverseGeocode($latitude, $longitude)
    // {
    //     $apiEndpoint = 'https://nominatim.openstreetmap.org/reverse';
    //     $apiUrl = sprintf(
    //         '%s?format=json&lat=%s&lon=%s',
    //         $apiEndpoint,
    //         urlencode($latitude),
    //         urlencode($longitude)
    //     );
    //     $options = [
    //         'http' => [
    //             'header' => "User-Agent: My-App\r\n",
    //         ],
    //     ];
    //     stream_context_set_default($options);
    //     $response = file_get_contents($apiUrl);
    //     if ($response === false) {
    //         return response()->json(['error' => 'Error fetching data from Nominatim API'], 500);
    //     }
    //     $data = json_decode($response, true);
    //     // dd($data['display_name']);
    //     if (isset($data['display_name'])) {
    //         return $data['display_name'];
    //     } else {
    //         return null;
    //     }
    // }
    // public static function reverseGeocode($latitude, $longitude)
    // {
    //     $apiEndpoint = 'https://nominatim.openstreetmap.org/reverse';
    //     $apiUrl = sprintf(
    //         '%s?format=json&lat=%s&lon=%s',
    //         $apiEndpoint,
    //         urlencode($latitude),
    //         urlencode($longitude)
    //     );
    
    //     // Use Guzzle for HTTP requests (install it with: composer require guzzlehttp/guzzle)
    //     $client = new \GuzzleHttp\Client();
    
    //     try {
    //         $response = $client->get($apiUrl, [
    //             'headers' => [
    //                 'User-Agent' => 'My-App',
    //             ],
    //         ]);
    
    //         $data = json_decode($response->getBody(), true);
    
    //         // Check for errors in the API response
    //         if (isset($data['display_name'])) {
    //             return $data['display_name'];
    //         } else {
    //             return null;
    //         }
    //     } catch (\GuzzleHttp\Exception\RequestException $e) {
    //         // Log the error for debugging purposes
    //         error_log('Error fetching data from Nominatim API: ' . $e->getMessage());
    
    //         // Return a generic error message to the client
    //         return response()->json(['error' => 'Error fetching data from Nominatim API. Please try again later.'], 500);
    //     }
    // }

    public static function reverseGeocode($latitude, $longitude)
    {
        $apiKey = "U0sY5E49ZLSNzkzfpJX2RYdBV7NKHrVX";
        $apiEndpoint = 'http://www.mapquestapi.com/geocoding/v1/reverse';
        $apiUrl = sprintf(
            '%s?key=%s&location=%s,%s',
            $apiEndpoint,
            $apiKey,
            urlencode($latitude),
            urlencode($longitude)
        );

        $client = new Client();

        try {
            $response = $client->get($apiUrl);
            $data = json_decode($response->getBody(), true);

            // dd($response);
            // Check for errors in the API response
            if (isset($data['results'][0]['locations'][0]['street'])) {
                // Adjust the array keys based on the response structure
                $addressComponents = $data['results'][0]['locations'][0];
                
                // Customize the address format based on your requirements
                $address = $addressComponents['street'] . ', ' . $addressComponents['adminArea5'] . ', ' . $addressComponents['adminArea3'];

                return $address;
            } else {
                return null;
            }
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            // Log the error for debugging purposes
            error_log('Error fetching data from MapQuest API: ' . $e->getMessage());

            // Return a JSON response with an error message
            return json_encode(['error' => 'Error fetching data from MapQuest API: ' . $e->getMessage()]);
        }
    }

    
}
