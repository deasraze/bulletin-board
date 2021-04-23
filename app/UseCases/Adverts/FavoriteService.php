<?php

namespace App\UseCases\Adverts;

use App\Entity\Adverts\Advert\Advert;
use App\Entity\User\User;

class FavoriteService
{
    public function add(int $userId, int $advertId): void
    {
        $user = $this->getUser($userId);
        $advert = $this->getAdvert($advertId);

        $user->addToFavorites($advert->id);
    }

    public function remove(int $userId, int $advertId): void
    {
        $user = $this->getUser($userId);
        $advert = $this->getAdvert($advertId);

        $user->removeFromFavorites($advert->id);
    }

    private function getUser(int $userId): User
    {
        return User::findOrFail($userId);
    }

    private function getAdvert(int $advertId): Advert
    {
        return Advert::findOrFail($advertId);
    }
}
