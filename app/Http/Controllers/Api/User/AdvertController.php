<?php

namespace App\Http\Controllers\Api\User;

use App\Entity\Adverts\Advert\Advert;
use App\Entity\Adverts\Category;
use App\Entity\Region;
use App\Http\Controllers\Controller;
use App\Http\Requests\Adverts\AttributesRequest;
use App\Http\Requests\Adverts\CreateRequest;
use App\Http\Requests\Adverts\EditRequest;
use App\Http\Requests\Adverts\PhotoRequest;
use App\Http\Resources\Adverts\AdvertDetailResource;
use App\Http\Resources\Adverts\AdvertListResource;
use App\UseCases\Adverts\AdvertService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class AdvertController extends Controller
{
    private AdvertService $service;

    public function __construct(AdvertService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $adverts = Advert::forUser($request->user())->orderByDesc('id')->paginate(20);

        return AdvertListResource::collection($adverts);
    }

    public function store(CreateRequest $request, Category $category, Region $region = null)
    {
        $advert = $this->service->create(
            $request->user()->id,
            $category->id,
            $region ? $region->id : null,
            $request,
        );

        return (new AdvertDetailResource($advert))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Advert $advert)
    {
        $this->checkAccess($advert);

        return new AdvertDetailResource($advert);
    }

    public function update(EditRequest $request, Advert $advert)
    {
        $this->checkAccess($advert);

        $this->service->edit($request, $advert->id);

        return new AdvertDetailResource(Advert::findOrFail($advert->id));
    }

    public function attributes(AttributesRequest $request, Advert $advert)
    {
        $this->checkAccess($advert);

        $this->service->editAttributes($request, $advert->id);

        return new AdvertDetailResource(Advert::findOrFail($advert->id));
    }

    public function photos(PhotoRequest $request, Advert $advert)
    {
        $this->checkAccess($advert);

        $this->service->addPhotos($request, $advert->id);

        return new AdvertDetailResource(Advert::findOrFail($advert->id));
    }

    public function send(Advert $advert)
    {
        $this->checkAccess($advert);

        $this->service->sendToModeration($advert->id);

        return new AdvertDetailResource(Advert::findOrFail($advert->id));
    }

    public function close(Advert $advert)
    {
        $this->checkAccess($advert);

        $this->service->close($advert->id);

        return new AdvertDetailResource(Advert::findOrFail($advert->id));
    }

    public function destroy(Advert $advert)
    {
        $this->checkAccess($advert);

        $this->service->remove($advert->id);

        return response()->json([], Response::HTTP_NO_CONTENT);
    }

    private function checkAccess(Advert $advert): void
    {
        if (! Gate::allows('manage-own-advert', $advert)) {
            abort(403);
        }
    }
}
