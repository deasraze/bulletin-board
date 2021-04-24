<?php

namespace App\UseCases\Profile;

use App\Entity\User\User;
use App\Http\Requests\Cabinet\PhoneVerifyRequest;
use App\Services\Sms\SmsSender;
use Carbon\Carbon;

class PhoneService
{
    private SmsSender $sms;

    public function __construct(SmsSender $sms)
    {
        $this->sms = $sms;
    }

    public function request(int $id): void
    {
        $user = $this->getUser($id);
        $token = $user->requestPhoneVerification(Carbon::now());

        $this->sms->send($user->phone, 'Phone verification token: ' . $token);
    }

    public function verify(int $id, PhoneVerifyRequest $request): void
    {
        $user = $this->getUser($id);

        $user->verifyPhone($request['token'], Carbon::now());
    }

    public function toggleAuth(int $id): bool
    {
        $user = $this->getUser($id);

        ($user->isPhoneAuthEnabled()) ? $user->disablePhoneAuth() : $user->enablePhoneAuth();

        return $user->isPhoneAuthEnabled();
    }

    private function getUser(int $id): User
    {
        return User::findOrFail($id);
    }
}
