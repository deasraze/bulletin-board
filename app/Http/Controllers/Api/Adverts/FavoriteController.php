<?php

namespace App\Http\Controllers\Api\Adverts;

use App\Entity\Adverts\Advert\Advert;
use App\Http\Controllers\Controller;
use App\UseCases\Adverts\FavoriteService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FavoriteController extends Controller
{
    private FavoriteService $service;

    public function __construct(FavoriteService $service)
    {
        $this->service = $service;
    }

    public function add(Request $request, Advert $advert)
    {
        $this->service->add($request->user()->id, $advert->id);

        return response()->json([], Response::HTTP_CREATED);
    }

    public function remove(Request $request, Advert $advert)
    {
        $this->service->remove($request->user()->id, $advert->id);

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
