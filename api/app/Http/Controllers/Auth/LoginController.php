<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Cabinet\TokenRequest;
use App\Models\User\User;
use App\UseCases\Profile\PhoneService;
use DomainException;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final class LoginController extends Controller
{
    use ThrottlesLogins;

    public function __construct(PhoneService $service)
    {
        $this->middleware('guest')->except('logout');

        $this->service = $service;
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            $this->sendLockoutResponse($request);
        }

        $authenticate = Auth::attempt(
            $request->only(['email', 'password']),
            $request->filled('remember')
        );

        if ($authenticate) {
            $request->session()->regenerate();
            $this->clearLoginAttempts($request);
            $user = Auth::user();
            if ($user->isWait()) {
                Auth::logout();
                return back()->with('error', 'You need to confirm your account. Please check your email.');
            }

            // Two factor auth
            if ($user->isPhoneAuthEnabled()) {
                Auth::logout();
                try {
                    $this->service->request($user->id);
                    $user = $user->getUser($user->id);
                } catch (DomainException $e) {
                    $this->logout($request);
                    return back()->with('error', $e->getMessage());
                }

                $request->session()->put('auth', [
                    'id' => $user->id,
                    'token' => $user->phone_verify_token,
                    'remember' => $request->filled('remember'),
                ]);

                return redirect()->route('login.phone');
            }

            return redirect()->intended(route('cabinet.home'));
        }

        $this->incrementLoginAttempts($request);

        throw ValidationException::withMessages(['email' => [trans('auth.failed')]]);
    }

    public function verify(TokenRequest $request)
    {
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            $this->sendLockoutResponse($request);
        }

        if (!$session = $request->session()->get('auth')) {
            throw new BadRequestHttpException('Missing token info.');
        }

        if ($request['token'] === $session['token']) {
            try {
                $this->service->verify($session['id'], $request['token']);
            } catch (DomainException $e) {
                return back()->with('error', $e->getMessage());
            }

            $request->session()->flush();
            $this->clearLoginAttempts($request);

            Auth::login(User::findOrFail($session['id']), $session['remember']);

            return redirect()->intended(route('cabinet.home'));
        }

        $this->incrementLoginAttempts($request);

        throw ValidationException::withMessages(['token' => ['Invalid auth token.']]);
    }

    public function phone()
    {
        return view('auth.phone');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        return redirect()->route('home');
    }

    protected function username()
    {
        return 'email';
    }
}
