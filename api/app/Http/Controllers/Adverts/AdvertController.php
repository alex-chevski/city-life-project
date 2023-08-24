<?php

declare(strict_types=1);

namespace App\Http\Controllers\Adverts;

use App\Http\Controllers\Controller;
use App\Http\Router\AdvertsPath;
use App\Models\Adverts\Advert\Advert;
use App\Models\Adverts\Category;
use Illuminate\Support\Facades\Auth;
use App\Models\Region;
use Illuminate\Support\Facades\Gate;

class AdvertController extends Controller
{
    public function index(AdvertsPath $path)
    {
        $query = Advert::active()->with(['category', 'region'])->orderByDesc('id');

        if ($category = $path->category) {
            $query->forCategory($category);
        }

        if ($region = $path->region) {
            $query->forRegion($region);
        }

        $regions = $region
            ? $region->children()->orderBy('name')->getModels()
            : Region::roots()->orderBy('name')->getModels();

        $categories = $category
            ? $category->children()->defaultOrder()->getModels()
            : Category::whereIsRoot()->defaultOrder()->getModels();

        $adverts = $query->paginate(20);

        return view('adverts.index', compact('category', 'region', 'categories', 'regions', 'adverts'));
    }

    public function show(Advert $advert)
    {
        if (!($advert->isActive() || Gate::allows('show-advert', $advert))) {
            abort(403);
        }

        $user = Auth::user();

        return view('adverts.show', compact('advert', 'user'));
    }

    public function phone(Advert $advert): string
    {
        if (!($advert->isActive() || Gate::allows('show-advert', $advert))) {
            abort(403);
        }

        return $advert->user->phone;
    }
}
