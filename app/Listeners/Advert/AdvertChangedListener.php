<?php

namespace App\Listeners\Advert;

use App\Events\Advert\ModerationPassed;
use App\Services\Search\AdvertIndexer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AdvertChangedListener
{
    private AdvertIndexer $indexer;

    public function __construct(AdvertIndexer $indexer)
    {
        $this->indexer = $indexer;
    }

    public function handle(ModerationPassed $event): void
    {
        $this->indexer->index($event->advert);
    }
}
