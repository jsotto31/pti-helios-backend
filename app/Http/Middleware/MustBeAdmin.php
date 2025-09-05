<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MustBeAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(!$request->user() || $request->user()->type != 'admin'){
            return response()->json([
                'message' => 'You are not authorized to access this resource.',
                'errors' => []
            ], 403);
        }

        return $next($request);
    }
}
