<?php

declare(strict_types=1);

namespace App\Models\Adverts\Advert;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $attribute_id
 * @property string $value
 */
class Value extends Model
{
    public $timestamps = false;
    protected $table = 'advert_advert_values';

    protected $fillable = ['attribute_id', 'value'];

    protected $primary_key = 'advert_id';
}
