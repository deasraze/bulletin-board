<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property int $id
 * @property string $name
 * @property string $last_name
 * @property string $email
 * @property string $phone
 * @property bool $phone_verified
 */
class ProfileResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'name' => [
                'first' => $this->name,
                'last' => $this->last_name,
            ],
            'phone' => [
                'number' => $this->phone,
                'verified' => $this->phone_verified
            ],
        ];
    }
}
