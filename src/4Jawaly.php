<?php

namespace Devhereco\4Jawaly;

use GuzzleHttp\Client;
use Devhereco\4Jawaly\Models\SmsHistory;

class 4Jawaly
{
    public static function send(int $phone, ?string $message)
    {
        $app_id = config('4jawaly.key');
        $app_sec = config('4jawaly.secret');
        $app_hash = base64_encode("{$app_id}:{$app_sec}");

        $messages = [
            "messages" => [
                [
                    "text" => $message,
                    "numbers" => [$phone],
                    "sender" => "4jawaly"
                ]
            ]
        ];

        $url = "https://api-sms.4jawaly.com/api/v1/account/area/sms/send";
        $headers = [
            "Accept: application/json",
            "Content-Type: application/json",
            "Authorization: Basic {$app_hash}"
        ];

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($messages));
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curl);
        $status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        $response_json = json_decode($response, true);

        if ($status_code == 200) {
            if (isset($response_json["messages"][0]["err_text"])) {
                echo $response_json["messages"][0]["err_text"];
            } else {
                echo "Sent Successfully " . " job id:" . $response_json["job_id"];
            }
        } elseif ($status_code == 400) {
            echo $response_json["message"];
        } elseif ($status_code == 422) {
            echo "Message body is empty; Please write something.";
        } else {
            echo "Blocked by CloudFlare, Status code: {$status_code}";
        }
    }

    public static function balance()
    {
        $client = new Client();
        $url = 'http://sms.malath.net.sa/api/getBalance.aspx?username=' . config('4jawaly.username') . '&password=' . config('4jawaly.password');

        $res = $client->get($url);
        $body = $res->getBody();
        $smsBalance = $body->read(3);

        return $smsBalance;
    }

}