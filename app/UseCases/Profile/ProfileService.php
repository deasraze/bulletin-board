<?php

namespace App\UseCases\Profile;

use App\Entity\User\User;
use App\Http\Requests\Cabinet\ProfileEditRequest;

class ProfileService
{
    public function edit(int $id, ProfileEditRequest $request): void
    {
        $user = User::findOrFail($id);
        $oldPhone = $user->phone;

        $user->update($request->only('name', 'last_name', 'phone'));

        if ($user->phone !== $oldPhone) {
            $user->unverifyPhone();
        }
    }
}
