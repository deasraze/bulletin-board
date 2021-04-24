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

    public function show(Request $request): ProfileResource
    {
        return new ProfileResource($request->user());
    }

    public function update(ProfileEditRequest $request): ProfileResource
    {
        $this->service->edit($request->user()->id, $request);

        $user = User::findOrFail($request->user()->id);

        return new ProfileResource($user);
    }
}
