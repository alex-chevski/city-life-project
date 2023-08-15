<?php

declare(strict_types=1);

namespace App\UseCases\Auth;

use App\Http\Requests\Auth\RegisterRequest;
use App\Mail\VerifyMail;
use App\Models\User\User;
use App\Services\Auth\Tokenizer;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Mail\Mailer as MailerInterface;

/**
 * Class RegisterService.
 * @author frostep
 */
class RegisterService
{
    private $mailer;
    private $dispatcher;
    private $tokenizer;
    private $user;
    private $date;

    /**
     * @param Mailer $mailer
     */
    public function __construct(
        MailerInterface $mailer,
        Dispatcher $dispatcher,
        Tokenizer $tokenizer,
        Carbon $date,
        User $user,
    ) {
        $this->mailer = $mailer;
        $this->dispatcher = $dispatcher;
        $this->tokenizer = $tokenizer;
        $this->user = $user;
        $this->date = $date;
    }

    /**
     * undocumented function.
     */
    public function register(RegisterRequest $request): void
    {
        $user = $this->user->registerCommand(
            $request['name'],
            $request['email'],
            $request['password'],
            $this->tokenizer->generate($this->date->copy(), 'email'),
        );

        $this->mailer->to($user->email)->send(new VerifyMail($user));
        $this->dispatcher->dispatch(new Registered($user));
    }

    /**
     * undocumented function.
     *
     * @param mixed $id
     */
    public function verify($id): void
    {
        /** @var User $user */
        $user = User::findOrFail($id);
        $user->verify();
        // You are welcome to the site dop
        // $this->mailer->to($user->email)->send(new VerifyMail($user));
    }
}
