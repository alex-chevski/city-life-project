<?php

declare(strict_types=1);

namespace App\Http\Resources\Adverts;

use App\Models\Adverts\Advert\Photo;
use App\Models\Adverts\Advert\Value;
use App\Models\Adverts\Attribute;
use App\Models\Adverts\Category;
use App\Models\Region;
use App\Models\User\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property int $id
 * @property string $title
 * @property string $content
 * @property int $price
 * @property string $address
 * @property Carbon $published_at
 * @property Carbon $expires_at
 *
 * @property User $user
 * @property Region $region
 * @property Category $category
 * @property Value[] $values
 * @property Collection|Photo[] $photos
 *
 * @method  mixed getValue($id)
 */
class AdvertDetailResource extends JsonResource
{
    public function toArray($request): array
    {
        // dd($this->photos->toArray());
        return [
            'id' => $this->id,
            'user' => [
                'name' => $this->user->name,
                'phone' => $this->user->phone,
            ],
            'category' => [
                'id' => $this->category->id,
                'name' => $this->category->name,
            ],
            'region' => $this->region ? [
                'id' => $this->region->id,
                'name' => $this->region->name,
            ] : [],
            'title' => $this->title,
            'content' => $this->content,
            'price' => $this->price,
            'address' => $this->address,
            'date' => [
                'published' => $this->published_at,
                'expires' => $this->expires_at,
            ],
            'values' => array_map(function (Attribute $attribute) {
                return [
                    'name' => $attribute->name,
                    'value' => $this->getValue($attribute->id),
                ];
            }, $this->category->allAttributes()),
            'photos' => array_map(static fn (Photo $photo) => $photo->file, $this->photos->toArray()),
        ];
    }
}
