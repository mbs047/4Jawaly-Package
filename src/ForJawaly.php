<?php

namespace Devhereco\ForJawaly;

use Illuminate\Support\Facades\Http;

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
        
        $url = "https://api-sms.4jawaly.com/api/v1/account/area/sms/send";
        $headers = [
            "Accept" => "application/json",
            "Content-Type" => "application/json",
            "Authorization" => "Basic {$app_hash}"
        ];
        
        $response = Http::withHeaders($headers)->post($url, $messages);
        $status_code = $response->status();
        
        $response_json = $response->json();
        
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
        $app_id = config('forjawaly.key');
        $app_sec = config('forjawaly.secret');
        $app_hash = base64_encode("{$app_id}:{$app_sec}");

        $query = [];
        $query["is_active"] = 1; // get active only
        $query["order_by"] = "id"; // package_points, current_points, expire_at or id (default)
        $query["order_by_type"] = "desc"; // desc or asc
        $query["page"] = 1 ;
        $query["page_size"] = 10 ;
        $query["return_collection"] = 1; // if you want to get all collection

        $client = new Client();
        
        $response = $client->request('GET', 'https://api-sms.4jawaly.com/api/v1account/area/me/packages', [
            'query' => $query,
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic '.$app_hash,
            ],
        ]);

        $data = json_decode($response->getBody(), true);

        var_dump($data);
    }

}
