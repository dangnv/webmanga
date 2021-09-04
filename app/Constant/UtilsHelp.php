<?php

namespace App\Constant;

use Illuminate\Support\Facades\Log;

class UtilsHelp
{
    public static function getSlugFromLink ($link) {
        $linkArr = explode('/', $link);
        $slugArr = explode('?', $linkArr[count($linkArr) - 1]);
        return $slugArr[0];
    }

    public static function checkStorage ()
    {
        $link = 'https://api.upcloud.com/1.3/object-storage/0630ee60-a85e-41b6-b2d7-a88262e76f5b';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $link);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_POSTFIELDS, "{\r\n  \"object_storage\": {\r\n    \"name\": \"data-object-storage\",\r\n    \"description\": \"data-object-storage\",\r\n    \"zone\": \"nl-ams1\",\r\n    \"access_key\": \"JT9WE882AZ548P5F1OZ2\",\r\n    \"secret_key\": \"nYuuz2hp+T+T8OdnX8ZefKcN8+VpyoT23IDrVjzP\"\r\n  }\r\n}");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "accept: application/json",
            "authorization: Basic aHVlcGhhbTk3Omh1RXBAMTIzNDU2",
            "cache-control: no-cache",
            "content-type: application/json",
            "postman-token: 31050867-6264-70eb-b1fd-720388179884"
        ));
        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);
        if ($err) {
            Log::warning("cURL Error #:" . $err);
        } else {
            $response = json_decode($response);
            if (!empty($response) && !empty($response->object_storage)) {
                $free = $response->object_storage->size - $response->object_storage->used_space;
                return $free;
            } else {
                return 0;
            }
        }
    }
}
