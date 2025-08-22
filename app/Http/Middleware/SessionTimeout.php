<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SessionTimeout
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $lastActivity = session('lastActivityTime');
            $timeout = 15 * 60; // 15 minutos

            if ($lastActivity && (time() - $lastActivity > $timeout)) {
                Auth::logout();
                session()->flush();

                return redirect()->route('login')
                    ->with('message', '⚠️ Sessão expirada, faça login novamente.');
            }

            session(['lastActivityTime' => time()]);
        }
        return $next($request);
    }
}


