<?php

namespace App\Http\Controllers\Cabinet\Adverts;

use App\Entity\Adverts\Advert\Advert;
use App\Http\Controllers\Controller;
use App\Http\Requests\Adverts\AttributesRequest;
use App\Http\Requests\Adverts\PhotoRequest;
use App\UseCases\Adverts\AdvertService;
use Illuminate\Support\Facades\Gate;

class ManageController extends Controller
{
    private AdvertService $service;

    public function __construct(AdvertService $service)
    {
        $this->service = $service;
    }

    public function attributesForm(Advert $advert)
    {
        $this->checkAccess($advert);
        return view('adverts.edit.attributes', compact('advert'));
    }

    public function attributes(AttributesRequest $request, Advert $advert)
    {
        $this->checkAccess($advert);

        try {
            $this->service->editAttributes($request, $advert->id);
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('adverts.show', $advert);
    }

    public function photosForm(Advert $advert)
    {
        $this->checkAccess($advert);

        return view('adverts.edit.photos', compact('advert'));
    }

    public function photos(PhotoRequest $request, Advert $advert)
    {
        $this->checkAccess($advert);

        try {
            $this->service->addPhotos($request, $advert->id);
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('adverts.show', $advert);
    }

    public function destroy(Advert $advert)
    {
        $this->checkAccess($advert);

        try {
            $this->service->remove($advert->id);
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back();
    }

    private function checkAccess(Advert $advert): void
    {
        if (!Gate::allows('manage-own-advert', $advert)) {
            abort(403);
        }
    }
}
