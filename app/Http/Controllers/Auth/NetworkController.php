<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\UseCases\Auth\NetworkService;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class NetworkController extends Controller
{
    private NetworkService $service;

    public function __construct(NetworkService $service)
    {
        $this->service = $service;
    }

    public function redirect(string $network)
    {
        return Socialite::driver($network)->redirect();
    }

    public function callback(string $network)
    {
        $data = Socialite::driver($network)->user();

        try {
            $user = $this->service->auth($data, $network);
            Auth::login($user);
        } catch (\DomainException $e) {
            return redirect()->route('login')->with('error', $e->getMessage());
        }

        return redirect()->intended();
    }
}
