<?php

declare(strict_types=1);

namespace App\Http\Controllers\Cabinet\Adverts;

use App\Http\Controllers\Controller;
use App\Http\Middleware\FilledProfile;

/**
 * Class AdvertController.
 * @author yourname
 */
class AdvertController extends Controller
{
    public function __construct()
    {
        $this->middleware(FilledProfile::class);
    }

    public function index()
    {
        return view('cabinet.adverts.index');
    }

    // public function create()
    // {
    // return view('cabinet.adverts.create');
    // }

    // public function edit()
    // {
    // return view('cabinet.adverts.edit');
    // }
}
