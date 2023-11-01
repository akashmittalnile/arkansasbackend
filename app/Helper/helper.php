<?php

use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\ChapterQuiz;
use App\Models\Notify;
use App\Models\Tag;
use Illuminate\Support\Carbon;
use Mockery\Undefined;

    if (!function_exists('sendNotification')) {
        function sendNotification($token, $data)
        {
            $url = 'https://fcm.googleapis.com/fcm/send';
            //$serverKey = env('FIREBASE_SERVER_KEY'); // ADD SERVER KEY HERE PROVIDED BY FCM
            $serverKey = 'AAAArLOz8H4:APA91bEFEqNkNlnmUsegFRwkU2nlX5FZ9z7G7LzLzuolkmqwLTIR0jijjmTMAKg1Ik4thMroyPU82NYsxzEVH4OXvhiZQLTgxjMamiIpPXSUy7N71A1OtcjXtVJlLHn3-nMkVNqHVpcV';
            $msg = array(
                'body'  => $data['msg'],
                'title' => $data['title'] ?? "ARKANSAS",
                'icon'  => "{{ asset('assets/website-images/logo-2.png') }}", //Default Icon
                'sound' => 'default'
            );
            $arr= array(
                'to' => $token,
                'notification' => $msg,
                'data' => $data,
                "priority" => "high"
            );
            $encodedData = json_encode($arr);
            $headers = [
                'Authorization:key=' . $serverKey,
                'Content-Type: application/json',
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            // Disabling SSL Certificate support temporarly
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
            // Execute post
            $result = curl_exec($ch);
            if ($result === FALSE) {
                die('Curl failed: ' . curl_error($ch));
            }
            // Close connection
            curl_close($ch);
        }
    }

    if (!function_exists('get_days_in_month')) {
        function get_days_in_month($month, $year)
        {
            if ($month == "02")
            {
                if ($year % 4 == 0) return 29;
                else return 28;
            }
            else if ($month == "01" || $month == "03" || $month == "05" || $month == "07" || $month == "08" || $month == "10" || $month == "12") return 31;
            else return 30;
        }
    }

    if (!function_exists('send_notification')) {
        function send_notification($token, $data)
        {
            $url = 'https://fcm.googleapis.com/fcm/send';
            $serverKey = env('FIREBASE_SERVER_KEY'); // ADD SERVER KEY HERE PROVIDED BY FCM
            $msg = array(
                'body'  => $data['msg'],
                'title' => "Arkansas",
                "icon" => "{{ asset('assets/superadmin-images/logo-2.png') }}",
                'sound' => 'default'
            );
            $arr = array(
                'to' => $token,
                'notification' => $msg,
                'data' => $data,
                "priority" => "high"
            );
            $encodedData = json_encode($arr);
            $headers = [
                'Authorization:key=' . $serverKey,
                'Content-Type: application/json',
            ];
    
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            // Disabling SSL Certificate support temporarly
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
            // Execute post
            $result = curl_exec($ch);
            if ($result === FALSE) {
                die('Curl failed: ' . curl_error($ch));
            }
            // Close connection
            curl_close($ch);
        }
    }

    if(!function_exists('array_has_dupes')) {
        function array_has_dupes($array) {
            return count($array) !== count(array_unique($array));
        }
    }

    if (!function_exists('successMsg')) {
        function successMsg($msg, $data = [])
        {
            return response()->json(['status' => true, 'message' => $msg, 'data' => $data]);
        }
    }

    if (!function_exists('errorMsg')) {
        function errorMsg($msg, $data = [])
        {
            return response()->json(['status' => false, 'message' => $msg, 'data' => $data]);
        }
    }

    if (!function_exists('getCategory')) {
        function getCategory($type, $id = null, $status = null)
        {
            $query = Category::where('type', $type);
            if(isset($id)){
                $query->where('id', $id);
            }
            if(isset($status)){
                $query->where('status', $status);
            }
            $query = $query->get();
            return $query;
        }
    }

    if (!function_exists('getTags')) {
        function getTags($type, $id = null, $status = null)
        {
            $query = Tag::where('type', $type);
            if(isset($id)){
                $query->where('id', $id);
            }
            if(isset($status)){
                $query->where('status', $status);
            }
            $query = $query->get();
            return $query;
        }
    }

    if (!function_exists('imageUpload')) {
        function imageUpload($request, $path, $name)
        {
            if ($request->file($name)) {
                $imageName = 'IMG_' . date('Ymd') . '_' . date('His') . '_' . rand(1000, 9999) . '.' . $request->image->extension();
                $request->image->move(public_path($path), $imageName);
                return $imageName;
            }
        }
    }

    if(! function_exists('encrypt_decrypt')){
        function encrypt_decrypt($action, $string) {
            $output = false;
            $encrypt_method = "AES-256-CBC";
            $secret_key = 'This is my secret key';
            $secret_iv = 'This is my secret iv';
            // hash
            $key = hash('sha256', $secret_key);
            $iv = substr(hash('sha256', $secret_iv), 0, 16);
            if ( $action == 'encrypt' ) {
                $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
                $output = base64_encode($output);
            } else if( $action == 'decrypt' ) {
                 $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
            }
            return $output;
        } 
    }

    if(! function_exists('getNotification')){
        function getNotification() {
            $notify = Notify::where('user_id', auth()->user()->id)->get();
            return $notify;
        } 
    }

    if(! function_exists('courseExpire')){
        function courseExpire($start, $end) {
            $now = Carbon::now();
            if ($now->between($start, $end)) 
                return false;
            else 
                return true;
        } 
    }

    if(! function_exists('dataSet')){
        function dataSet($val) {
            if($val == '' || $val == null) return 'NA';
            else return $val;
        } 
    }