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

    /**
     * @OA\Get(
     *     path="/user/favorites",
     *     tags={"Favorites"},
     *     @OA\Response(
     *         response=200,
     *         description="Success response",
     *         @OA\Schema(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/AdvertList")
     *         ),
     *     ),
     *     security={{"Bearer": {}, "OAuth2": {}}}
     * )
     */
    public function index(Request $request)
    {
        $adverts = Advert::favoredByUser($request->user())->orderByDesc('id')->paginate(20);

        return AdvertListResource::collection($adverts);
    }

    /**
     * @OA\Delete(
     *     path="/user/favorites/{advertId}",
     *     tags={"Favorites"},
     *     @OA\Parameter(
     *         name="advertId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *           type="integer",
     *         ),
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Success response",
     *     ),
     *     security={{"Bearer": {}, "OAuth2": {}}}
     * )
     */
    public function remove(Request $request, Advert $advert)
    {
        $this->service->remove($request->user()->id, $advert->id);

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
