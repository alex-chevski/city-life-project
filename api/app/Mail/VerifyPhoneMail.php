<?php

declare(strict_types=1);

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

final class VerifyPhoneMail extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    private string $text;
    private string $number;

    /**
     * Create a new message instance.
     * @param mixed $number
     * @param mixed $text
     */
    public function __construct($number, $text)
    {
        $this->number = $number;
        $this->text  = $text;
    }

    /**
     * undocumented function.
     */
    public function build()
    {
        return $this
            ->subject('Код подтверждения')
            ->markdown('mail.verify-phone-mail', ['number' => $this->number, 'text'=> $this->text]);
    }
}
