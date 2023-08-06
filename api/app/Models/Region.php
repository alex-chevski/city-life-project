<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Region
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property int|null $parent_id
 */
class Region extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'slug', 'parent_id'];

    /**
     * undocumented function
     *
     * @return void
     */
    public function parent()
    {
        return $this->belongsTo(static::class, 'parent_id', 'id');
    }

    /**
     * undocumented function
     *
     * @return void
     */
    public function children()
    {
        return $this->hasMany(static::class, 'parent_id', 'id');
    }
}
