<?php

namespace App\Http\Controllers\Adverts;

use App\Entity\Adverts\Advert\Advert;
use App\Entity\Adverts\Category;
use App\Entity\Region;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class AdvertController extends Controller
{
    public function index(Region $region = null, Category $category = null)
    {
        $query = Advert::active()->with(['category', 'region'])->orderByDesc('published_at');

        if ($region) {
            $query->forRegion($region);
        }
        if ($category) {
            $query->forCategory($category);
        }

        $adverts = $query->paginate(20);

        $regions = $region
            ? $region->children()->orderBy('name')->getModels()
            : Region::roots()->orderBy('name')->getModels();

        $categories = $category
            ? $category->children()->defaultOrder()->getModels()
            : Category::whereIsRoot()->defaultOrder()->getModels();

        return view('adverts.index', compact('adverts', 'regions', 'categories', 'region', 'category'));
    }

    public function show(Advert $advert)
    {
        $this->checkAccess($advert);

        return view('adverts.show', compact('advert'));
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
