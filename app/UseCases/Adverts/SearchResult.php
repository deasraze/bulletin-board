<?php

namespace App\UseCases\Adverts;

use Illuminate\Contracts\Pagination\Paginator;

class SearchResult
{
    public Paginator $adverts;
    public array $regionsCounts;
    public array $categoriesCounts;

    public function __construct(Paginator $adverts, array $regionsCounts, array $categoriesCounts)
    {
        $this->adverts = $adverts;
        $this->regionsCounts = $regionsCounts;
        $this->categoriesCounts = $categoriesCounts;
    }
}
