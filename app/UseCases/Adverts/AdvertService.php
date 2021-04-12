<?php

namespace App\UseCases\Adverts;

use App\Entity\Adverts\Advert\Advert;
use App\Entity\Adverts\Category;
use App\Entity\Region;
use App\Entity\User;
use App\Http\Requests\Adverts\AttributesRequest;
use App\Http\Requests\Adverts\CreateRequest;
use App\Http\Requests\Adverts\PhotoRequest;
use App\Http\Requests\Adverts\RejectRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdvertService
{
    public function create(int $userId, int $categoryId, ?int $regionId, CreateRequest $request): Advert
    {
        $user = User::findOrFail($userId);
        $category = Category::findOrFail($categoryId);
        $region = $regionId ? Region::findOrFail($regionId) : null;

        return DB::transaction(function () use ($request, $user, $category, $region) {
            $advert = Advert::make([
                'title' => $request['title'],
                'content' => $request['content'],
                'price' => $request['price'],
                'address' => $request['address'],
                'status' => Advert::STATUS_DRAFT,
            ]);

            $advert->user()->associate($user);
            $advert->category()->associate($category);
            $advert->region()->associate($region);

            $advert->saveOrFail();

            foreach ($category->allAttributes() as $attribute) {
                $value = $request['attributes'][$attribute->id] ?? null;

                if ($value !== null) {
                    $advert->values()->create([
                        'attribute_id' => $attribute->id,
                        'value' => $value,
                    ]);
                }
            }

            return $advert;
        });
    }

    public function addPhotos(PhotoRequest $request, int $id): void
    {
        $advert = $this->getAdvert($id);

        DB::transaction(function () use ($request, $advert) {
            foreach ($request['files'] as $file) {
                $advert->photos()->create([
                    'file' => $file->store('adverts'),
                ]);
            }
        });
    }

    public function sendToModeration(int $id): void
    {
        $advert = $this->getAdvert($id);
        $advert->sendToModeration();
    }

    public function moderate(int $id): void
    {
        $advert = $this->getAdvert($id);
        $advert->moderate(Carbon::now());
    }

    public function reject(RejectRequest $request, int $id): void
    {
        $advert = $this->getAdvert($id);
        $advert->reject($request['reason']);
    }

    public function editAttributes(AttributesRequest $request, $id): void
    {
        $advert = $this->getAdvert($id);

        DB::transaction(function () use ($advert, $request) {
            $advert->values()->delete();

            foreach ($advert->category->allAttributes() as $attribute) {
                $value = $request['attributes'][$attribute->id] ?? null;

                if ($value !== null) {
                    $advert->values()->create([
                        'attribute_id' => $attribute->id,
                        'value' => $value,
                    ]);
                }
            }
        });
    }

    public function expire(Advert $advert): void
    {
        $advert->expire();
    }

    public function close(int $id): void
    {
        ($this->getAdvert($id))->close();
    }

    public function remove(int $id): void
    {
        $advert = $this->getAdvert($id);
        $advert->delete();
    }

    private function getAdvert(int $id): Advert
    {
        return Advert::findOrFail($id);
    }
}
