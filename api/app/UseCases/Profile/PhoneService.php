<?php

namespace App\UseCases\Profile;

use App\Models\User\User;
use App\Services\Sms\SmsSender;
use App\Services\Auth\Tokenizer;
use Carbon\Carbon;

class PhoneService
{
    private $sms;
    private Tokenizer $tokenizer;
    private Carbon $date;
    private User $user;


    public function __construct(SmsSender $sms, User $user, Tokenizer $tokenizer, Carbon $date)
    {
        $this->sms = $sms;
        $this->tokenizer = $tokenizer;
        $this->date = $date;
        $this->user = $user;
    }

    public function request($id)
    {
        $user = $this->user->getUser($id);

        $user->requestPhoneVerification($this->tokenizer, $this->date->copy());


        $this->sms->send($user->phone, $user->phone_verify_token);
    }

    public function verify($id, string $token)
    {
        $user = $this->user->getUser($id);
        $user->verifyPhone(
            $token,
            $this->date->copy(),
            $this->tokenizer->generate($user->phone_verify_token_expire, 'default', $user->phone_verify_token),
        );
    }
}
