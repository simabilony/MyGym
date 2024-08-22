<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next, String $role): Response
    {
        if(auth()->user()->role !== $role) {
            return redirect()->route('dashboard');
        }
        return $next($request);
    }
}
