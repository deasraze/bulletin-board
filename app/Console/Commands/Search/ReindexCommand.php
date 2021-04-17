<?php

namespace App\Console\Commands\Search;

use App\Entity\Adverts\Advert\Advert;
use App\Services\Search\AdvertIndexer;
use Illuminate\Console\Command;

class ReindexCommand extends Command
{
    protected $signature = 'search:reindex';

    protected $description = 'Reindex all active adverts';

    private AdvertIndexer $indexer;

    public function __construct(AdvertIndexer $indexer)
    {
        $this->indexer = $indexer;
        parent::__construct();
    }

    public function handle(): int
    {
        $this->indexer->clear();

        foreach (Advert::active()->orderBy('id')->cursor() as $advert) {
            $this->indexer->index($advert);
        }

        $this->info('Success!');

        return 0;
    }
}
