<?php

namespace App\Http\Controllers\Api\User;

use App\Entity\User\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\Cabinet\ProfileEditRequest;
use App\Http\Resources\User\ProfileResource;
use App\UseCases\Profile\ProfileService;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    private ProfileService $service;

    public function __construct(ProfileService $service)
    {
        $this->service = $service;
    }

    /**
     * @OA\Get(
     *     path="/user",
     *     tags={"Profile"},
     *     @OA\Response(
     *         response=200,
     *         description="Success response",
     *         @OA\Schema(ref="#/components/schemas/Profile"),
     *     ),
     *     security={{"bearerAuth": {}, "OAuth2": {}}}
     * )
     */
    public function show(Request $request): ProfileResource
    {
        return new ProfileResource($request->user());
    }

    /**
     * @OA\Put(
     *     path="/user",
     *     tags={"Profile"},
     *     @OA\Parameter(name="body", in="path", required=true, @OA\Schema(ref="#/components/schemas/ProfileEditRequest")),
     *     @OA\Response(
     *         response=200,
     *         description="Success response",
     *     ),
     *     security={{"bearerAuth": {}, "OAuth2": {}}}
     * )
     */
    public function update(ProfileEditRequest $request): ProfileResource
    {
        $this->service->edit($request->user()->id, $request);

        $user = User::findOrFail($request->user()->id);

        return new ProfileResource($user);
    }
}
