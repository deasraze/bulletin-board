<?php

namespace App\Http\Controllers\Api\User;

use App\Entity\User\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\Cabinet\ProfileEditRequest;
use App\UseCases\Profile\ProfileService;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    private ProfileService $service;

    public function __construct(ProfileService $service)
    {
        $this->service = $service;
    }

    public function show(Request $request): array
    {
        /* @var User $user */
        $user = $request->user();

        return [
            'id' => $user->id,
            'email' => $user->email,
            'name' => [
                'first' => $user->name,
                'last' => $user->last_name,
            ],
            'phone' => [
                'number' => $user->phone,
                'verified' => $user->phone_verified
            ],
        ];
    }

    public function update(ProfileEditRequest $request): array
    {
        $this->service->edit($request->user()->id, $request);

        $user = User::findOrFail($request->user()->id);

        return [
            'id' => $user->id,
            'email' => $user->email,
            'name' => [
                'first' => $user->name,
                'last' => $user->last_name,
            ],
            'phone' => [
                'number' => $user->phone,
                'verified' => $user->phone_verified
            ],
        ];
    }
}
