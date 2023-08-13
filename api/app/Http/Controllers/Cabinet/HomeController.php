<?php

declare(strict_types=1);

namespace App\Http\Controllers\Cabinet;

use App\Http\Controllers\Controller;

final class HomeController extends Controller
{
    public function index()
    {
        return view('cabinet.home');
    }
}
