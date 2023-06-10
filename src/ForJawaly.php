<?php

namespace Devhereco\ForJawaly;

use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;

class ForJawaly
{
    private static $baseUrl = "https://api-sms.4jawaly.com/api/v1";

    private static function getAuthorizationHeader()
    {
        $app_id = config('forjawaly.key');
        $app_sec = config('forjawaly.secret');
        $app_hash = base64_encode("{$app_id}:{$app_sec}");

        return "Basic {$app_hash}";
    }

    public static function send(int $phone, ?string $message)
    {
        $messages = [
            "messages" => [
                [
                    "text" => $message,
                    "numbers" => [$phone],
                    "sender" => config('forjawaly.sender')
                ]
            ]
        ];

        $url = self::$baseUrl . "/account/area/sms/send";
        $headers = [
            "Accept" => "application/json",
            "Content-Type" => "application/json",
            "Authorization" => self::getAuthorizationHeader()
        ];

        $response = Http::withHeaders($headers)->post($url, $messages);
        $status_code = $response->status();

        $response_json = $response->json();

        if ($status_code === 200) {
            if (isset($response_json["messages"][0]["err_text"])) {
                echo $response_json["messages"][0]["err_text"];
            } else {
                echo "Sent Successfully. Job ID: " . $response_json["job_id"];
            }
        } elseif ($status_code === 400) {
            echo $response_json["message"];
        } elseif ($status_code === 422) {
            echo "Message body is empty. Please write something.";
        } else {
            echo "Blocked by CloudFlare. Status code: {$status_code}";
        }
    }

    public static function balance()
    {
        $query = [
            "is_active" => 1,
            "order_by" => "id",
            "order_by_type" => "desc",
            "page" => 1,
            "page_size" => 10,
            "return_collection" => 1
        ];

        $client = new Client();

        $response = $client->request('GET', self::$baseUrl . "/account/area/me/packages", [
            'query' => $query,
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => self::getAuthorizationHeader(),
            ],
        ]);

        $data = json_decode($response->getBody(), true);

        echo $data['total_balance'];
    }
}
