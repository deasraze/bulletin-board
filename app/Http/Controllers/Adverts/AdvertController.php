<?php

namespace App\Http\Controllers\Adverts;

use App\Entity\Adverts\Advert\Advert;
use App\Entity\Adverts\Category;
use App\Entity\Region;
use App\Http\Controllers\Controller;
use App\Http\Requests\Adverts\SearchRequest;
use App\Http\Router\AdvertsPath;
use App\UseCases\Adverts\SearchService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AdvertController extends Controller
{
    private SearchService $search;

    public function __construct(SearchService $search)
    {
        $this->search = $search;
    }

    public function index(SearchRequest $request, AdvertsPath $path)
    {
        $region = $path->region;
        $category = $path->category;

        $result = $this->search->search($region, $category, $request, 20, $request->query('page', 1));

        $adverts = $result->adverts;
        $regionsCounts = $result->regionsCounts;
        $categoriesCounts = $result->categoriesCounts;

        $query = $region ? $region->children() : Region::roots();
        $regions = $query->orderBy('name')->getModels();

        $query = $category ? $category->children() : Category::whereIsRoot();
        $categories = $query->defaultOrder()->getModels();

        $regions = array_filter($regions, function (Region $region) use ($regionsCounts) {
            return array_key_exists($region->id, $regionsCounts);
        });

        $categories = array_filter($categories, function (Category $category) use ($categoriesCounts) {
            return array_key_exists($category->id, $categoriesCounts);
        });

        return view('adverts.index', compact(
            'adverts',
            'region',
            'regions',
            'category',
            'categories',
            'regionsCounts',
            'categoriesCounts'
        ));
    }

    public function show(Advert $advert)
    {
        $this->checkAccess($advert);
        $user = Auth::user();

        return view('adverts.show', compact('advert', 'user'));
    }

    public function phone(Advert $advert): string
    {
        $this->checkAccess($advert);

        return $advert->user->phone;
    }

    private function checkAccess(Advert $advert): void
    {
        if (!($advert->isActive() || Gate::allows('show-advert', $advert))) {
            abort(403);
        }
    }
}
