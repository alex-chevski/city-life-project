<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ResetPassword\EmailRequest;
use App\UseCases\Auth\ResetPassword\RequestService;
use DomainException;

final class ForgotPasswordController extends Controller
{
    private RequestService $service;

    public function __construct(RequestService $service)
    {
        $this->service = $service;
    }

    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    public function sendResetLinkEmail(EmailRequest $request)
    {
        try {
            $this->service->requestResetLink($request['email']);

            return redirect()->route('login')
                ->with('success', 'Check your email and click on the link to reset password.');
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
