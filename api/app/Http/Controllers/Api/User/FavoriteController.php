<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\Adverts\AdvertDetailResource;
use App\Models\Adverts\Advert\Advert;
use App\UseCases\Adverts\FavoriteService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    private $service;

    public function __construct(FavoriteService $service)
    {
        $this->service = $service;
        $this->middleware('auth');
    }

    public function index()
    {
        $adverts = Advert::favoredByUser(Auth::user())->orderByDesc('id')->paginate(20);

        return AdvertDetailResource::collection($adverts);
    }

    public function remove(Advert $advert)
    {
        $this->service->remove(Auth::id(), $advert->id);

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
