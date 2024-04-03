<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Cors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $allowedOrigins = ['http://localhost:3000', 'https://pet-connect.website'];

        $origin = $request->headers->get('Origin');
        if (in_array($origin, $allowedOrigins)) {
            return $next($request)
            ->header('Accept', 'application/json')
            ->header('Content-Type', 'application/json')
            ->header('Access-Control-Allow-Origin', $origin)
            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
            ->header('Access-Control-Allow-Credentials', true)
            ->header('Access-Control-Allow-Headers', 'Accept, X-Requested-With, Content-Type, X-Token-Auth, Authorization');
        }

        return $next($request);
    }
}
