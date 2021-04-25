<?php

namespace App\Http\Controllers\Api\Adverts;

use App\Entity\Adverts\Advert\Advert;
use App\Entity\Adverts\Category;
use App\Entity\Region;
use App\Http\Controllers\Controller;
use App\Http\Requests\Adverts\SearchRequest;
use App\Http\Resources\Adverts\AdvertDetailResource;
use App\Http\Resources\Adverts\AdvertListResource;
use App\UseCases\Adverts\SearchService;
use Illuminate\Support\Facades\Gate;

class AdvertController extends Controller
{
    private SearchService $search;

    public function __construct(SearchService $search)
    {
        $this->search = $search;
    }

    public function index(SearchRequest $request)
    {
        $region = $request->query('region') ? Region::findOrFail($request->query('region')) : null;
        $category = $request->query('category') ? Category::findOrFail($request->query('category')) : null;

        $result = $this->search->search($region, $category, $request, 20, $request->query('page', 1));

        return AdvertListResource::collection($result->adverts);
    }

    public function show(Advert $advert): AdvertDetailResource
    {
        if (! ($advert->isActive() || Gate::allows('show-advert', $advert))) {
            abort(403);
        }

        return new AdvertDetailResource($advert);
    }
}
