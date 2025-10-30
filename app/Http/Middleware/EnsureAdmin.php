<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(!$request->user()) {
            return response(['message' => 'Unauthorized'], 401);
        }

        if ($request->user()->role !== 'admin') {
            return response(['message' => 'Forbidden'], 403);
        }
        return $next($request);
    }
}
