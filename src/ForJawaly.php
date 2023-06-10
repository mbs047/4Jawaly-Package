<?php

namespace Devhereco\ForJawaly;

use Illuminate\Support\Facades\Http;

class ForJawaly
{
    protected $base_url = "https://api-sms.4jawaly.com/api/v1";
    protected $app_id;
    protected $secret;
    protected $app_hash;


    public function __constract()
    {
        $this->app_id = config('forjawaly.key');
        $this->secret = config('forjawaly.secret');
        $this->app_hash = base64_encode("{$this->app_id}:{$this->secret}")
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
        
        $url = "{$this->base_url}/account/area/sms/send";
        $headers = [
            "Accept" => "application/json",
            "Content-Type" => "application/json",
            "Authorization" => "Basic {$this->app_hash}"
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
        $query = [];
        $query["is_active"] = 1;
        $query["order_by"] = "id";
        $query["order_by_type"] = "desc";
        $query["page"] = 1 ;
        $query["page_size"] = 10 ;
        $query["return_collection"] = 1;

        $client = new Client();
        $response = $client->request('GET', "{$this->base_url}/account/area/me/packages", [
            'query' => $query,
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                "Authorization" => "Basic {$this->app_hash}"
            ],
        ]);

        $data = json_decode($response->getBody(), true);

        var_dump($data);
    }

}
