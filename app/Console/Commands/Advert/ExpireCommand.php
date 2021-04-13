<?php

namespace App\Console\Commands\Advert;

use App\UseCases\Adverts\AdvertService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ExpireCommand extends Command
{
    protected $signature = 'advert:expire';

    protected $description = 'Close expired adverts';

    private AdvertService $service;

    public function __construct(AdvertService $service)
    {
        $this->service = $service;
        parent::__construct();
    }

    public function handle(): int
    {
        $success = 0;

        foreach (Advert::active()->where('expired_at', '<', Carbon::now())->cursor() as $advert) {
            try {
                $this->service->expire($advert);
            } catch (\DomainException $e) {
                $this->error($e->getMessage());
                $success = 1;
            }
        }

        return $success;
    }
}
