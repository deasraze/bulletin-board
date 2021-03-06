<?php

namespace App\UseCases\Adverts;

use App\Entity\Adverts\Advert\Advert;
use App\Entity\Adverts\Category;
use App\Entity\Region;
use App\Entity\User\User;
use App\Events\Advert\ModerationPassed;
use App\Http\Requests\Adverts\AttributesRequest;
use App\Http\Requests\Adverts\CreateRequest;
use App\Http\Requests\Adverts\EditRequest;
use App\Http\Requests\Adverts\PhotoRequest;
use App\Http\Requests\Adverts\RejectRequest;
use Carbon\Carbon;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Facades\DB;

class AdvertService
{
    private Dispatcher $dispatcher;

    public function __construct(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

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
                    'file' => $file->store('adverts', 'public'),
                ]);
            }

            $advert->update();
        });
    }

    public function edit(EditRequest $request, int $id): void
    {
        $advert = $this->getAdvert($id);
        $advert->update($request->only([
            'title',
            'content',
            'price',
            'address',
        ]));
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

        $this->dispatcher->dispatch(new ModerationPassed($advert));
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

            $advert->update();
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
