<?php

namespace App\Http\Controllers\Api\User;

use App\Entity\Adverts\Advert\Advert;
use App\Http\Controllers\Controller;
use App\Http\Resources\Adverts\AdvertListResource;
use App\UseCases\Adverts\FavoriteService;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    private FavoriteService $service;

    public function __construct(FavoriteService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $adverts = Advert::favoredByUser($request->user())->orderByDesc('id')->paginate(20);

        return AdvertListResource::collection($adverts);
    }

    public function remove(Request $request, Advert $advert)
    {
        $this->service->remove($request->user()->id, $advert->id);

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
