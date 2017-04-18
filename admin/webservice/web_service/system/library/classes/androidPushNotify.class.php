<?php

//androidPushNotify.cls
//////////////////////////////////////////////////////////
# This Class will be use to send push notification to Android device.
//////////////////////////////////////////////////////////
class NicAndroidPushNotify {

    //put your code here
    // constructor
    function __construct() {
        
    }

    function LatestSendAndroidNotificaiton($token, $message, $apiKey, $verse_id = 0) {
        $url = 'https://android.googleapis.com/gcm/send';
        $api_key1 = $apiKey;
        $device_token = $token;

        $headers = array(
            'Authorization: key=' . $api_key1,
            'Content-Type: application/json'
        );


        $data = array(
            'registration_ids' => array($device_token),
            'data' => array(
                'title' => 'USBible',
                'message' => $message,
                'key' => '1',
                'ticker' => 'USBible',
                'verse_id' => $verse_id,
                "sound" => 'default'
            )
        );


        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);

        if ($headers) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($ch);

        preg_match("/(error=)([\\w|-]+)/", $response, $matches);
        /* echo '<pre>';
          print_r($response);
          echo '</pre>'; */
        if (!$matches[2]) {
            return false;
        }

        curl_close($ch);

        return true;
    }

}
