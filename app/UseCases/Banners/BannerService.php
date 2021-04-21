<?php

namespace App\UseCases\Banners;

use App\Entity\Adverts\Category;
use App\Entity\Banner\Banner;
use App\Entity\Region;
use App\Entity\User;
use App\Http\Requests\Banner\CreateRequest;
use App\Http\Requests\Banner\EditRequest;
use App\Http\Requests\Banner\RejectRequest;
use App\Services\Banner\CostCalculator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class BannerService
{
    private CostCalculator $calculator;

    public function __construct(CostCalculator $calculator)
    {
        $this->calculator = $calculator;
    }

    public function getRandomForView()
    {

    }

    public function create(User $user, Category $category, ?Region $region, CreateRequest $request): Banner
    {
        $banner = Banner::make([
            'name' => $request['name'],
            'limit' => $request['limit'],
            'url' => $request['url'],
            'format' => $request['format'],
            'file' => $request->file('file')->store('banners', 'public'),
            'status' => Banner::STATUS_DRAFT,
        ]);

        $banner->user()->associate($user);
        $banner->category()->associate($category);
        $banner->region()->associate($region);

        $banner->saveOrFail();

        return $banner;
    }

    public function changeFile()
    {

    }

    public function editByOwner(EditRequest $request, int $id): void
    {
        $banner = $this->getBanner($id);

        if (! $banner->canBeChanged()) {
            throw new \DomainException('Unable to edit the banner.');
        }

        $banner->update([
            'name' => $request['name'],
            'limit' => $request['limit'],
            'url' => $request['url'],
        ]);
    }

    public function editByAdmin(EditRequest $request, int $id): void
    {
        $banner = $this->getBanner($id);

        $banner->update([
            'name' => $request['name'],
            'limit' => $request['limit'],
            'url' => $request['url'],
        ]);
    }

    public function sendToModeration(int $id): void
    {
        $banner = $this->getBanner($id);
        $banner->sendToModeration();
    }

    public function cancelModeration(int $id): void
    {
        $banner = $this->getBanner($id);
        $banner->cancelModeration();
    }

    public function moderate(int $id): void
    {
        $banner = $this->getBanner($id);
        $banner->moderate();
    }

    public function reject(RejectRequest $request, int $id): void
    {
        $banner = $this->getBanner($id);
        $banner->reject($request['reason']);
    }

    public function order(int $id): Banner
    {
        $banner = $this->getBanner($id);
        $cost = $this->calculator->calc($banner->limit);

        $banner->order($cost);

        return $banner;
    }

    public function pay(int $id): void
    {
        $banner = $this->getBanner($id);
        $banner->pay(Carbon::now());
    }

    public function click(Banner $banner): void
    {
        $banner->click();
    }

    public function removeByOwner(int $id): void
    {
        $banner = $this->getBanner($id);

        if (! $banner->canBeRemoved()) {
            throw new \DomainException('Unable to remove the banner.');
        }

        $banner->delete();

        Storage::delete($banner->file);
    }

    public function removeByAdmin(int $id): void
    {
        $banner = $this->getBanner($id);

        $banner->delete();

        Storage::delete($banner->file);
    }

    private function getBanner(int $id): Banner
    {
        return Banner::findOrFail($id);
    }
}
