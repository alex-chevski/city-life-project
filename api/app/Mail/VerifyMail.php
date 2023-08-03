<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\User\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

final class VerifyMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    private $user;

    /**
     * Create a new message instance.
     */
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
            ->subject('Signup Confirmation')
            ->markdown('mail.verify-mail', ['user'=>$this->user]);
    }
}
