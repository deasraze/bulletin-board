<?php

namespace App\Http\Controllers\Admin\Adverts;

use App\Entity\Adverts\Advert\Advert;
use App\Http\Controllers\Controller;
use App\Http\Requests\Adverts\AttributesRequest;
use App\Http\Requests\Adverts\EditRequest;
use App\Http\Requests\Adverts\PhotoRequest;
use App\Http\Requests\Adverts\RejectRequest;
use App\UseCases\Adverts\AdvertService;
use Illuminate\Http\Request;

class AdvertController extends Controller
{
    private AdvertService $service;

    public function __construct(AdvertService $service)
    {
        $this->middleware('can:manage-adverts');
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $query = Advert::orderByDesc('updated_at');

        if (!empty($value = $request->get('id'))) {
            $query->where('id', $value);
        }

        if (!empty($value = $request->get('title'))) {
            $query->where('title', 'like', '%' . $value . '%');
        }

        if (!empty($value = $request->get('user'))) {
            $query->where('user_id', $value);
        }

        if (!empty($value = $request->get('region'))) {
            $query->where('region_id', $value);
        }

        if (!empty($value = $request->get('category'))) {
            $query->where('category_id', $value);
        }

        if (!empty($value = $request->get('status'))) {
            $query->where('status', $value);
        }

        $adverts = $query->paginate(20);

        $statuses = Advert::statusesList();

        return view('admin.adverts.adverts.index', compact('adverts', 'statuses'));
    }

    public function editForm(Advert $advert)
    {
        return view('adverts.edit.advert', compact('advert'));
    }

    public function edit(EditRequest $request, Advert $advert)
    {
        try {
            $this->service->edit($request, $advert->id);
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('adverts.show', $advert);
    }

    public function attributesForm(Advert $advert)
    {
        return view('adverts.edit.attributes', compact('advert'));
    }

    public function attributes(AttributesRequest $request, Advert $advert)
    {
        try {
            $this->service->editAttributes($request, $advert->id);
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('adverts.show', $advert);
    }

    public function photosForm(Advert $advert)
    {
        return view('adverts.edit.photos', compact('advert'));
    }

    public function photos(PhotoRequest $request, Advert $advert)
    {
        try {
            $this->service->addPhotos($request, $advert->id);
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('adverts.show', $advert);
    }

    public function rejectForm(Advert $advert)
    {
        return view('admin.adverts.adverts.reject', compact('advert'));
    }

    public function reject(RejectRequest $request, Advert $advert)
    {
        try {
            $this->service->reject($request, $advert->id);
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('adverts.show', $advert);
    }

    public function moderate(Advert $advert)
    {
        try {
            $this->service->moderate($advert->id);
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

        return redirect()->route('admin.adverts.adverts.index');
    }
}
