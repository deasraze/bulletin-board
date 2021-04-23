<?php

namespace App\UseCases\Auth;

use App\Entity\User\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Contracts\User as NetworkUser;

class NetworkService
{
    private Dispatcher $dispatcher;

    public function __construct(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function auth(NetworkUser $data, string $network): User
    {
        if ($user = User::byNetwork($data->getId(), $network)->first()) {
            return $user;
        }

        if ($data->getEmail() && User::where('email', $data->getEmail())->exists()) {
            throw new \DomainException('User with this email is already registered.');
        }

        $user = DB::transaction(function () use ($network, $data) {
            return User::registerByNetwork($data->getId(), $network);
        });

        $this->dispatcher->dispatch(new Registered($user));

        return $user;
    }
}
