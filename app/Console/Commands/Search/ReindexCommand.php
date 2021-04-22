<?php

namespace App\Console\Commands\Search;

use App\Entity\Adverts\Advert\Advert;
use App\Entity\Banner\Banner;
use App\Services\Search\AdvertIndexer;
use App\Services\Search\BannerIndexer;
use Illuminate\Console\Command;

class ReindexCommand extends Command
{
    protected $signature = 'search:reindex';

    protected $description = 'Reindex all indexes for elastic search';

    private AdvertIndexer $adverts;
    private BannerIndexer $banners;

    public function __construct(AdvertIndexer $adverts, BannerIndexer $banners)
    {
        $this->adverts = $adverts;
        $this->banners = $banners;
        parent::__construct();
    }

    public function handle(): int
    {
        $this->reindexAdverts();
        $this->reindexBanners();

        $this->info('Success!');

        return 0;
    }

    private function reindexAdverts(): void
    {
        $this->adverts->clear();

        foreach (Advert::active()->orderBy('id')->cursor() as $advert) {
            $this->adverts->index($advert);
        }
    }

    private function reindexBanners(): void
    {
        $this->banners->clear();

        foreach (Banner::active()->orderBy('id')->cursor() as $banner) {
            $this->banners->index($banner);
        }
    }
}
