<?php

namespace App\Entity\Adverts;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

/**
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
    use NodeTrait, HasFactory;

    protected $table = 'advert_categories';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'slug',
        'parent_id'
    ];

    public function getPath(): string
    {
        return \implode('/', \array_merge(
            $this->ancestors()->defaultOrder()->pluck('slug')->toArray(),
            [$this->slug]
        ));
    }

    public function allAttributes(): array
    {
        return array_merge($this->parentAttributes(), $this->attributes()->orderBy('sort')->getModels());
    }

    public function parentAttributes(): array
    {
        return $this->parent ? $this->parent->allAttributes() : [];
    }

    public function attributes()
    {
        return $this->hasMany(Attribute::class, 'category_id', 'id');
    }
}
