<?php

declare(strict_types=1);

namespace App\Models\Adverts\Advert;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Value.
 * @author yourname
 * @property int $id
 * @property string $file
 */
class Photo extends Model
{
    public $timestamps = false;
    protected $table = 'advert_advert_photos';

    protected $fillable = ['file'];
}
