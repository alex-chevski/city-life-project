<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ConfirmsPasswords;

final class ConfirmPasswordController extends Controller
{
    use ConfirmsPasswords;

    protected $redirectTo = '/cabinet';

    public function __construct()
    {
        $this->middleware('auth');
    }
}
