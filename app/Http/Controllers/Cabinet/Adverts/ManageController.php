<?php

namespace App\Http\Controllers\Cabinet\Adverts;

use App\Entity\Adverts\Advert\Advert;
use App\Http\Controllers\Controller;
use App\Http\Middleware\FilledProfile;
use App\Http\Requests\Adverts\AttributesRequest;
use App\Http\Requests\Adverts\PhotoRequest;
use App\UseCases\Adverts\AdvertService;

class ManageController extends Controller
{
    private AdvertService $service;

    public function __construct(AdvertService $service)
    {
        $this->middleware(['auth', FilledProfile::class]);
        $this->service = $service;
    }

    public function attributes(Advert $advert)
    {
        return view('adverts.edit.attributes', compact('advert'));
    }

    public function updateAttributes(AttributesRequest $request, Advert $advert)
    {
        try {
            $this->service->editAttributes($request, $advert->id);
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('adverts.show', $advert);
    }

    public function photos(Advert $advert)
    {
        return view('adverts.edit.photos', compact('advert'));
    }

    public function updatePhotos(PhotoRequest $request, Advert $advert)
    {
        try {
            $this->service->addPhotos($request, $advert->id);
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('adverts.show', $advert);
    }

    public function destroy(Advert $advert)
    {
        try {
            $this->service->remove($advert->id);
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back();
    }
}
