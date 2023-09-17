<?php

declare(strict_types=1);

namespace App\Exceptions;

use DomainException;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Throwable;

final class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(static function (Throwable $e): void {});

        $this->renderable(static function (Exception $exception, Request $request) {
            if ($exception instanceof DomainException && $request->expectsJson()) {
                return response()->json([
                    'message' => $exception->getMessage(),
                ], Response::HTTP_BAD_REQUEST);
            }
        });
    }
}
