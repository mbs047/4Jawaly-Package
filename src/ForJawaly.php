<?php

namespace Devhereco\ForJawaly;

use GuzzleHttp\Client;

class ForJawaly
{
    public static function send(int $phone, ?string $message)
    {
        $app_id = config('forjawaly.key');
        $app_sec = config('forjawaly.secret');
        $app_hash = base64_encode("{$app_id}:{$app_sec}");
        
        $messages = [
            "messages" => [
                [
                    "text" => $message,
                    "numbers" => [$phone],
                    "sender" => config('forjawaly.sender')
                ]
            ]
        ];
        
        $url = "https://api-sms.forjawaly.com/api/v1/account/area/sms/send";
        $headers = [
            "Accept" => "application/json",
            "Content-Type" => "application/json",
            "Authorization" => "Basic {$app_hash}"
        ];
        
        $client = new Client();
        $response = $client->post($url, [
            'headers' => $headers,
            'json' => $messages
        ]);
        
        $status_code = $response->getStatusCode();
        $response_json = json_decode($response->getBody(), true);
        
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
        $url = 'http://sms.malath.net.sa/api/getBalance.aspx?username=' . config('forjawaly.username') . '&password=' . config('forjawaly.password');

        $res = $client->get($url);
        $body = $res->getBody();
        $smsBalance = $body->read(3);

        return $smsBalance;
    }

}
