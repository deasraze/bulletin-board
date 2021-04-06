<?php

namespace App\Http\Controllers\Cabinet;

use App\Http\Controllers\Controller;
use App\Services\Sms\SmsSender;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PhoneController extends Controller
{
    private SmsSender $sms;

    public function __construct(SmsSender $sms)
    {
        $this->sms = $sms;
    }

    public function request(Request $request)
    {
        $user = Auth::user();

        try {
            $token = $user->requestPhoneVerification(Carbon::now());
            $this->sms->send($user->phone, 'Phone verification token: ' . $token);
        } catch (\DomainException $e) {
            $request->session()->flash('error', sprintf(
                '%s Please try again after %ds.',
                $e->getMessage(),
                $user->phone_verify_token_expire->diffInSeconds(Carbon::now())
            ));
        }

        return redirect()->route('cabinet.profile.phone');
    }

    public function form()
    {
        $user = Auth::user();

        return view('cabinet.profile.phone', compact('user'));
    }

    public function verify(Request $request)
    {
        $this->validate($request, [
            'token' => 'required|string|max:255',
        ]);

        $user = Auth::user();

        try {
            $user->verifyPhone($request['token'], Carbon::now());
        } catch (\DomainException $e) {
            return redirect()->route('cabinet.profile.phone')
                ->with('error', $e->getMessage())
                ->withInput($request->only('token'));
        }

        return redirect()->route('cabinet.profile.home')->with('success', 'Success. Phone verified!');
    }
}
