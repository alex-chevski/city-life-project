<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ResetPassword\PasswordRequest;
use App\UseCases\Auth\ResetPassword\ResetService;
use DomainException;
use Illuminate\Http\Request;

final class ResetPasswordController extends Controller
{
    public function __construct(private ResetService $service)
    {
        $this->middleware('guest');
        $this->service = $service;
    }

    public function showResetForm(Request $request)
    {
        $token = $request->route()->parameter('token');
        return view('auth.passwords.reset')->with(
            ['token' => $token]
        );
    }

    public function reset(PasswordRequest $request)
    {
        try {
            $this->service->reset($request['token'], $request['password']);
            return redirect()->route('login')
                ->with('success', 'Password reset success');
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
