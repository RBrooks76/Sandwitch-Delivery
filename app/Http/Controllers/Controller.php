<?php

namespace App\Http\Controllers;

use App\Setting;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Mail;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    
    
    public function SendNotificationSendPushNotification($UserToken, $Title, $Notification, $sound = 1, $screen = "", $ExtraData = array()){
		$url = "https://fcm.googleapis.com/fcm/send";
        $device_tokens= array();
        
        $BadgeCount = 0;
        
        if(isset($ExtraData['UnreadBadge'])){
            $BadgeCount = $ExtraData['UnreadBadge'];
        }
        
        $notification = array(
                                "body" => $Notification,
                                "title" => $Title,
                                "sound" => $sound,
                                "badge" => $BadgeCount
                            );
		
        $data["title"] = $Title;
        $data["content"] = $Notification;
        $data["screen"] = $screen;
        
        foreach($ExtraData as $K => $V){
            $data[$K] = $V;
        }
		
        $arrayToSend = array('to' => $UserToken, "notification" => $notification, "data" => $data);
        $json = json_encode($arrayToSend);
        
        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: key=AAAALnCyG5U:APA91bHZL5vfPkKVRDS6jI9Z4XIBLYNQeeDiUJAhi-NTU8p-nuYiIc2qQ8ZvPWVlmPbFz-1JdLQfkPQ45a9NmGqAFG2nKDaac7XAc8WZnO9RM6v8kv56Qk_58pUH4hVjrNjpApEF4EJY';
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST,"POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);
        
        curl_close( $ch );
    }

    public function upladImage($request, $dir = 'user')
    {
        $img = $request;
        $imageName = time() . '.' . $img->getClientOriginalExtension();
        $direction = public_path('/upload/' . $dir . '/');
        $img->move($direction, $imageName);
        return $saveImge = 'upload/' . $dir . '/' . $imageName;
    }

    public function get_DistanceLatLog($lat1, $lon1, $lat2, $lon2, $unit)
    {
        if (($lat1 == $lat2) && ($lon1 == $lon2)) {
            return 0;
        } else {
            $theta = $lon1 - $lon2;
            $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
            $dist = acos($dist);
            $dist = rad2deg($dist);
            $miles = $dist * 60 * 1.1515;
            $unit = strtoupper($unit);

            if ($unit == "K") {
                return ($miles * 1.609344);
            } else if ($unit == "N") {
                return ($miles * 0.8684);
            } else {
                return $miles;
            }
        }
        /*
echo distance(32.9697, -96.80322, 29.46786, -98.53506, "M") . " Miles<br>";
echo distance(32.9697, -96.80322, 29.46786, -98.53506, "K") . " Kilometers<br>";
echo distance(32.9697, -96.80322, 29.46786, -98.53506, "N") . " Nautical Miles<br>";*/
    }

    public
    function upladImage1($request, $dir = 'user')
    {
        $img = $request;
        $imageName = time() . '.' . $img->getClientOriginalExtension();
        $direction = public_path('/public/upload/' . $dir . '/');
        $img->move($direction, $imageName);
        return $saveImge = 'upload/' . $dir . '/' . $imageName;
    }

    public
    function IP_Address()
    {
        return \Request::ip();
    }

    public
    function create_slug($x)
    {
        return str_replace(' ', '-', $x);
    }

    public
    function create_keywords($x)
    {
        return $x;
    }

    public
    function url_link()
    {
        return Setting::first()->public;
    }

    public
    function url_base_path()
    {
        return '/public/upload/';
    }

    public
    function RanomGmail($n)
    {
        $domain = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
        $len = strlen($domain);
        $generated_string = "";
        for ($i = 0; $i < $n; $i++) {
            $index = rand(0, $len - 1);
            $generated_string = $generated_string . $domain[$index];
        }
        return $generated_string;
    }

    public
    function RandomOrderId($n)
    {
        $domain2 = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz@#$&*";
        $domain = "0123456789";
        $len = strlen($domain);
        $generated_string = "";
        for ($i = 0; $i < $n; $i++) {
            $index = rand(0, $len - 1);
            $generated_string = $generated_string . $domain[$index];
        }
        return $generated_string;
    }

    public
    function PublicPa()
    {
        return Setting::first()->public;
    }

    public
    function CurrentID()
    {
        return Auth::user()->id;
    }

    public
    function changeEnv($data = array())
    {
        if (count($data) > 0) {

            // Read .env-file
            $env = file_get_contents(base_path() . '/.env');

            // Split string on every " " and write into array
            $env = preg_split('/\s+/', $env);;

            // Loop through given data
            foreach ((array)$data as $key => $value) {

                // Loop through .env-data
                foreach ($env as $env_key => $env_value) {

                    // Turn the value into an array and stop after the first split
                    // So it's not possible to split e.g. the App-Key by accident
                    $entry = explode("=", $env_value, 2);

                    // Check, if new key fits the actual .env-key
                    if ($entry[0] == $key) {
                        // If yes, overwrite it with the new one
                        $env[$env_key] = $key . "=" . $value;
                    } else {
                        // If not, keep the old one
                        $env[$env_key] = $env_value;
                    }
                }
            }

            // Turn the array back to an String
            $env = implode("\n", $env);

            // And overwrite the .env with the new data
            file_put_contents(base_path() . '/.env', $env);

            return true;
        } else {
            return false;
        }
    }

    public
    static function successjson($msg, $status = 200)
    {
        return response()->json(['status' => 'success', 'errors' => 0, 'data' => $msg], $status)
            ->header('Content-type', 'application/json');
    }

    public
    function send_Email_template($name_templage, $to_name, $to_email, $text)
    {

        $from_email = env('MAIL_USERNAME');

        $data = array('text' => $text);

        Mail::send(['html' => 'emails.text'], $data, function ($message) use ($to_name, $to_email, $from_email, $name_templage) {
            $message->to($to_email, $to_name)
                ->subject($name_templage);
            $message->from($from_email, $name_templage);
        });
    }

    public function date_get($date,$format){
        return explode("/",$date)[$format];
    }

    public
    static function errorjson($msg, $status = 400)
    {
        $msgCount = 1;
        if (is_array($msg)) {
            $msgCount = sizeof($msgCount);
        } else if ($msg instanceof Collection) {
            $msgCount = $msg->count();
        }
        /*
        if($msg instanceof MessageBag){
            $msg = $msg->first();
        }*/
        return response()->json(['status' => 'error', 'errors' => $msgCount, 'data' => $msg], $status)
            ->header('Content-type', 'application/json');
    }
}
