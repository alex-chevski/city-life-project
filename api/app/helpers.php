<?php

declare(strict_types=1);

use App\Http\Router\AdvertsPath;
use App\Models\Adverts\Category;
use App\Models\Region;

if (!function_exists('adverts_path')) {
    function adverts_path(?Region $region, ?Category $category)
    {
        return app()->make(AdvertsPath::class)
            ->withRegion($region)
            ->withCategory($category);
    }
}
