<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\UseCases\Auth\RegisterService;
use Symfony\Component\HttpFoundation\Response;

class RegisterController extends Controller
{
    private RegisterService $service;

    public function __construct(RegisterService $service)
    {
        $this->service = $service;
    }

    public function register(RegisterRequest $request)
    {
        $this->service->register($request);

        return response()->json([
            'success', 'Check your email and click on the link to verify.',
        ], Response::HTTP_CREATED);
    }
}
