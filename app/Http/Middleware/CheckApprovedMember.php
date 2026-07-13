<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckApprovedMember
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::user()->approved == 1) {
            return $next($request);
        }
        flash(translate('Your account is pending admin approval. This feature will be available once your account is approved.'))->error();
        return back();
    }
}
