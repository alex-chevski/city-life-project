<?php

declare(strict_types=1);

namespace App\UseCases\Profile;

use App\Models\User\User;
use App\Services\Auth\Tokenizer\TokenizerSms;
use App\Services\Sms\SmsSender;
use Carbon\Carbon;

class PhoneService
{
    private $sms;
    private TokenizerSms $tokenizer;
    private Carbon $now;
    private User $user;

    public function __construct(SmsSender $sms, User $user, TokenizerSms $tokenizer, Carbon $now)
    {
        $this->sms = $sms;
        $this->tokenizer = $tokenizer;
        $this->now = $now;
        $this->user = $user;
    }

    public function request($id): void
    {
        $user = $this->user->getUser($id);

        $user->requestPhoneVerification($this->tokenizer, $this->now->copy());

        $this->sms->send($user->phone, $user->phone_verify_token);
    }

    public function verify($id, string $token): void
    {
        $user = $this->user->getUser($id);
        $user->verifyPhone(
            $token,
            $this->now->copy(),
            $this->tokenizer->default($user->phone_verify_token),
        );
    }

    /**
     * undocumented function.
     *
     * @param mixed $id
     */
    public function toggleAuth($id): bool
    {
        $user = $this->user->getUser($id);
        if ($user->isPhoneAuthEnabled()) {
            $user->disablePhoneAuth();
        } else {
            $user->enablePhoneAuth();
        }

        return $user->isPhoneAuthEnabled();
    }
}
