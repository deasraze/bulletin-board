<?php

namespace App\Listeners\Advert;

use App\Notifications\Advert\ModerationPassedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\Advert\ModerationPassed;

class ModerationPassedListener
{
    public function handle(ModerationPassed $event): void
    {
        $advert = $event->advert;

        $advert->user->notify(new ModerationPassedNotification($advert));
    }
}
