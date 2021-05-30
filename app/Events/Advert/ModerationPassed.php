<?php

namespace App\Events\Advert;

use App\Entity\Adverts\Advert\Advert;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ModerationPassed
{
    use Dispatchable, SerializesModels;

    public Advert $advert;

    public function __construct(Advert $advert)
    {
        $this->advert = $advert;
    }
}
