<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class TwoFactorVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
   
public function handle($request, Closure $next)
{
    $user = Auth::user();

    if (!$user || !$user->two_factor_verified_at) {
        return redirect()->route('login')->withErrors([
            '2fa' => 'Precisa verificar o 2FA antes de acessar esta área.'
        ]);
    }

    return $next($request);
}


}
