<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

final class HomeController extends Controller
{
    /**
     * undocumented function
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('can:admin-panel');
    }

    public function index()
    {
        return view('admin.home');
    }
}
