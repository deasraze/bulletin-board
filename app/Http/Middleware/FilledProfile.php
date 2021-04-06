<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FilledProfile
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (!$user->hasFilledProfile()) {
            return redirect()
                ->route('cabinet.profile.home')
                ->with('error', 'Please fill your profile and verify your phone.');
        }

        return $next($request);
    }
}
