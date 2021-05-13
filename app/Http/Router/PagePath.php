<?php

namespace App\Http\Router;

use App\Entity\Page;
use Illuminate\Contracts\Routing\UrlRoutable;
use Illuminate\Support\Facades\Cache;

class PagePath implements UrlRoutable
{
    public ?Page $page = null;

    public function withPage(?Page $page): self
    {
        $clone = clone $this;
        $clone->page = $page;

        return $clone;
    }

    public function getRouteKey()
    {
        if (! $this->page) {
            throw new \BadMethodCallException('Empty page.');
        }

        return Cache::tags(Page::class)
            ->rememberForever('page_path_' . $this->page->id, function () {
                return $this->page->getPath();
            });
    }

    public function getRouteKeyName(): string
    {
        return 'page_path';
    }

    public function resolveRouteBinding($value, $field = null)
    {
        $chunks = \explode('/', $value);
        $page = null;

        do {
            $slug = reset($chunks);
            $next = Page::where('slug', $slug)
                ->where('parent_id', $page ? $page->id : null)
                ->first();

            if ($slug && $next) {
                $page = $next;
                \array_shift($chunks);
            }
        } while (!empty($slug) && !empty($next));

        if (\count($chunks) > 0) {
            abort(404);
        }

        return $this->withPage($page);
    }

    public function resolveChildRouteBinding($childType, $value, $field)
    {
    }
}
