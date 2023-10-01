<?php

declare(strict_types=1);

namespace App\UseCases\Adverts;

use App\Events\Advert\AdvertClosed;
use App\Events\Advert\ModerationPassed;
use App\Events\Advert\ModerationRejected;
use App\Http\Requests\Adverts\AttributesRequest;
use App\Http\Requests\Adverts\CreateRequest;
use App\Http\Requests\Adverts\EditRequest;
use App\Http\Requests\Adverts\PhotosRequest;
use App\Http\Requests\Adverts\RejectRequest;
use App\Models\Adverts\Advert\Advert;
use App\Models\Adverts\Category;
use App\Models\Region;
use App\Models\User\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Symfony\Component\EventDispatcher\EventDispatcher;

class AdvertService
{
    public function __construct(private EventDispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function create($userId, $categoryId, $regionId, CreateRequest $request): Advert
    {
        /** @var User $user */
        $user = User::findOrFail($userId);
        /** @var Category $category */
        $category = Category::findOrFail($categoryId);
        /** @var Region $region */
        $region = $regionId ? Region::findOrFail($regionId) : null;

        return DB::transaction(static function () use ($request, $user, $category, $region) {
            /** @var Advert $advert */
            $advert = Advert::make([
                'title' => $request['title'],
                'content' => $request['content'],
                'price' => $request['price'],
                'address' => $request['address'],
                'status' => Advert::STATUS_DRAFT,
            ]);

            $advert->user()->associate($user);
            $advert->category()->associate($category);
            $advert->region()->associate($region);

            $advert->saveOrFail();

            foreach ($category->allAttributes() as $attribute) {
                $value = $request['attributes'][$attribute->id] ?? null;
                if (!empty($value)) {
                    $advert->setValue($attribute->id, $value);
                }
            }

            return $advert;
        });
    }

    public function addPhotos($id, PhotosRequest $request): void
    {
        $advert = $this->getAdvert($id);

        DB::transaction(static function () use ($request, $advert): void {
            foreach ($request['files'] as $file) {
                $advert->addPhoto($file->store('adverts', 'public'));
            }
        });
    }

    public function edit($id, EditRequest $request): void
    {
        $advert = $this->getAdvert($id);
        $advert->update($request->only([
            'title',
            'content',
            'price',
            'address',
        ]));
    }

    public function sendToModeration($id): void
    {
        $advert = $this->getAdvert($id);
        $advert->sendToModeration();
    }

    public function moderate($id): void
    {
        $advert = $this->getAdvert($id);
        $advert->moderate(Carbon::now());
        event(new ModerationPassed($advert));
    }

    public function reject($id, RejectRequest $request): void
    {
        $advert = $this->getAdvert($id);
        $advert->reject($request['reason']);
        event(new ModerationRejected($advert));
    }

    public function editAttributes($id, AttributesRequest $request): void
    {
        $advert = $this->getAdvert($id);

        DB::transaction(static function () use ($request, $advert): void {
            $advert->values()->delete();
            foreach ($advert->category->allAttributes() as $attribute) {
                $value = $request['attributes'][$attribute->id] ?? null;
                if (!empty($value)) {
                    $advert->setValue($attribute->id, $value);
                }
            }
            $advert->update();
        });
    }

    public function expire(Advert $advert): void
    {
        $advert->expire();
        // Later
        // event();
    }

    public function close($id): void
    {
        $advert = $this->getAdvert($id);
        $advert->close();
    }

    public function remove($id): void
    {
        $advert = $this->getAdvert($id);

        $files = $advert->photos;
        $advert->delete();

        event(new AdvertClosed($advert, $files));
    }

    private function getAdvert($id): Advert
    {
        return Advert::findOrFail($id);
    }
}
