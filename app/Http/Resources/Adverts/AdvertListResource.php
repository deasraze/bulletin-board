<?php

namespace App\Http\Resources\Adverts;

use App\Entity\Adverts\Advert\Photo;
use App\Entity\Adverts\Category;
use App\Entity\Region;
use App\Entity\User\User;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property int $id
 * @property string $title
 * @property int $price
 * @property Carbon $published_at
 *
 * @property User $user
 * @property Region $region
 * @property Category $category
 * @property Photo[] $photos
 */
class AdvertListResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'price' => $this->price,
            'date' => $this->published_at,
            'photo' => $this->photos()->first(),
            'user' => [
                'name' => $this->user->name,
            ],
            'category' => [
                'id' => $this->category->id,
                'name' => $this->category->name,
            ],
            'region' => $this->region ? [
                'id' => $this->region->id,
                'name' => $this->region->name,
            ] : [],
        ];
    }
}
