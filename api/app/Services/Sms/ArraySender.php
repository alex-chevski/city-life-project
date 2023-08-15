<?php

namespace App\Services\Sms;

use GuzzleHttp\Client;

// for tests look at phpunit.xml
class ArraySender implements SmsSender
{
    private $messages = [];

    public function send($number, $text): void
    {
        $this->messages[] = [
            'to' => '+' . trim($number, '+'),
            'msg' => $text,
        ];
    }

    /**
     * undocumented function
     *
     */
    public function getMessages(): array
    {
        return $this->messages;
    }
}
