<?php

declare(strict_types=1);

namespace App\Services\Sms;

use GuzzleHttp\Client;
use Illuminate\Contracts\Queue\ShouldQueue;
use InvalidArgumentException;

// service https://sms.ru/
class SmsRu implements SmsSender, ShouldQueue
{
    private $appId;
    private $url;
    private $client;

    public function __construct($appId, $url = 'https://sms.ru/sms/send')
    {
        if (empty($appId)) {
            throw new InvalidArgumentException('Sms appId must be set.');
        }

        $this->appId = $appId;
        $this->url = $url;
        $this->client = new Client();
    }

    public function send($number, $text): void
    {
        $this->client->post($this->url, [
            'form_params' => [
                'api_id' => $this->appId,
                'to' => '+' . trim($number, '+'),
                'msg' => $text,
            ],
        ]);
    }
}
