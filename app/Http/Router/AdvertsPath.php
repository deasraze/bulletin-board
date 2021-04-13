<?php

namespace App\Http\Router;

use App\Entity\Adverts\Category;
use App\Entity\Region;
use Illuminate\Contracts\Routing\UrlRoutable;

class AdvertsPath implements UrlRoutable
{
    public ?Region $region = null;

    public ?Category $category = null;

    public function withRegion(?Region $region): self
    {
        $clone = clone $this;
        $clone->region = $region;

        return $clone;
    }

    public function withCategory(?Category $category): self
    {
        $clone = clone $this;
        $clone->category = $category;

        return $clone;
    }

    public function getRouteKey()
    {
        $segments = [];

        if ($this->region) {
            $segments[] = $this->region->getPath();
        }

        if ($this->category) {
            $segments[] = $this->category->getPath();
        }

        return \implode('/', $segments);
    }

    public function getRouteKeyName(): string
    {
        return 'adverts_path';
    }

    public function resolveRouteBinding($value, $field = null)
    {
        $chunks = \explode('/', $value);

        $region = null;
        do {
            $slug = reset($chunks);
            $next = Region::where('slug', $slug)
                ->where('parent_id', $region ? $region->id : null)
                ->first();

            if ($slug && $next) {
                $region = $next;
                \array_shift($chunks);
            }
        } while (!empty($slug) && !empty($next));

        $category = null;
        do {
            $slug = reset($chunks);
            $next = Category::where('slug', $slug)
                ->where('parent_id', $category ? $category->id : null)
                ->first();

            if ($slug && $next) {
                $category = $next;
                \array_shift($chunks);
            }
        } while (!empty($slug) && !empty($next));

        if (!empty($chunks)) {
            abort(404);
        }

        return $this
            ->withRegion($region)
            ->withCategory($category);
    }

    public function resolveChildRouteBinding($childType, $value, $field)
    {
    }
}
