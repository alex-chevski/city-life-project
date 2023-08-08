<?php

namespace App\Models\Adverts;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Category
 * @author yourname
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property int|null $parent_id
 *
 * @property int $depth
 * @property Category $parent
 * @property Category[] $children
 * @property Attribute[] $attributes
 */
class Category extends Model
{
    use NodeTrait;
    use HasFactory;

    protected $table = 'advert_categories';

    public $timestamps = false;

    protected $fillable = ['name', 'slug', 'parent_id'];

    /**
     * undocumented function
     *
     * @return void
     */
    public function attributes()
    {
        return $this->hasMany(Attribute::class, 'category_id', 'id');
    }

    public function parentAttributes(): array
    {
        return $this->parent ? $this->parent->allAttributes() : [];
    }

    public function allAttributes(): array
    {
        return array_merge($this->parentAttributes(), $this->attributes()->orderBy('sort')->getModels());
    }
}