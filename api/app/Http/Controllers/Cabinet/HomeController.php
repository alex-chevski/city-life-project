<?php

declare(strict_types=1);

namespace App\Http\Controllers\Cabinet;

use App\Http\Controllers\Controller;

final class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('cabinet.home');
    }
}
