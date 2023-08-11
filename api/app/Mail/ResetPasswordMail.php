<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\User\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * undocumented function.
     */
    public function build()
    {
        return $this
            ->subject('Письмо для сброса пароля')
            ->markdown('mail.reset-password', ['user'=>$this->user]);
    }
}
