<?php

namespace App\Services\Sms;

use GuzzleHttp\Client;

class SmsRu implements SmsSender
{
    private string $apiId;
    private string $url;
    private Client $client;

    public function __construct(string $apiId, string $url = 'https://sms.ru/sms/send')
    {
        if (strlen($apiId) === 0) {
            throw new \InvalidArgumentException('Sms appId must be set.');
        }

        $this->apiId = $apiId;
        $this->url = $url;
        $this->client = new Client();
    }

    public function send($number, $text): void
    {
        $this->client->post($this->url, [
            'form_params' => [
                'api_id' => $this->apiId,
                'to' => trim($number, '+'),
                'msg' => $text,
                'json' => 1,
            ],
        ]);
    }
}
