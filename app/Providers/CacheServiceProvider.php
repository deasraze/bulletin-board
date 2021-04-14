<?php

namespace App\Providers;

use App\Entity\Adverts\Category;
use App\Entity\Region;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

class CacheServiceProvider extends ServiceProvider
{
    private array $classes = [
        Region::class,
        Category::class,
    ];

    public function boot(): void
    {
        foreach ($this->classes as $class) {
            $this->registerFlusher($class);
        }
    }

    private function registerFlusher(string $class): void
    {
        $flush = function () use ($class) {
            Cache::tags($class)->flush();
        };

        /** @var Model $class */
        $class::created($flush);
        $class::updated($flush);
        $class::saved($flush);
        $class::deleted($flush);
    }
}
