<?php

namespace App\Http\Controllers\Cabinet;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cabinet\PhoneVerifyRequest;
use App\UseCases\Profile\PhoneService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PhoneController extends Controller
{
    private PhoneService $service;

    public function __construct(PhoneService $service)
    {
        $this->service = $service;
    }

    public function request(Request $request)
    {
        $user = Auth::user();

        try {
            $this->service->request($user->id);
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

    public function verify(PhoneVerifyRequest $request)
    {
        try {
            $this->service->verify(Auth::id(), $request);
        } catch (\DomainException $e) {
            return redirect()->route('cabinet.profile.phone')
                ->with('error', $e->getMessage())
                ->withInput($request->only('token'));
        }

        return redirect()->route('cabinet.profile.home')->with('success', 'Success. Phone verified!');
    }

    public function auth()
    {
        $this->service->toggleAuth(Auth::id());

        return redirect()->route('cabinet.profile.home');
    }
}
