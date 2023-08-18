<?php

declare(strict_types=1);

namespace App\Services\Sms;

interface SmsSender
{
    public function send($number, $text): void;
}
