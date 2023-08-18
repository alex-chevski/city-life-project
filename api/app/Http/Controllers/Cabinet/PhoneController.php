<?php

declare(strict_types=1);

namespace App\Http\Controllers\Cabinet;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cabinet\TokenRequest;
use App\UseCases\Profile\PhoneService;
use DomainException;
use Illuminate\Support\Facades\Auth;

class PhoneController extends Controller
{
    private $service;

    public function __construct(PhoneService $service)
    {
        $this->service = $service;
    }

    public function request()
    {
        try {
            $this->service->request(Auth::id());
        } catch (DomainException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        $user = Auth::user();

        return redirect()->route('cabinet.profile.phone');
    }

    public function form()
    {
        $user = Auth::user();

        return view('cabinet.profile.phone', compact('user'));
    }

    public function verify(TokenRequest $request)
    {
        try {
            $this->service->verify(Auth::id(), $request['token']);
        } catch (DomainException $e) {
            return redirect()->route('cabinet.profile.phone')->with('error', $e->getMessage());
        }

        return redirect()->route('cabinet.profile.home');
    }

    public function auth()
    {
        try {
            $this->service->toggleAuth(Auth::id());
        } catch (DomainException $e) {
            return redirect()->route('cabinet.profile.home')->with('error', $e->getMessage());
        }

        return redirect()->route('cabinet.profile.home');
    }
}
