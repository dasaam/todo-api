<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BasicAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $AUTH_USER = env('BASIC_USER', 'user');
        $AUTH_PASS = env('BASIC_PASS', 'password');

        $header = $request->header('Authorization');
        if (!$header || !str_starts_with($header, 'Basic ')) {
            return response()->json(['error' => 'Unauthorized'], 401)
                ->header('WWW-Authenticate', 'Basic realm="Tasks"');
        }

        $encoded = substr($header, 6);
        $decoded = base64_decode($encoded);
        [$user, $pass] = explode(':', $decoded, 2);

        if ($user !== $AUTH_USER || $pass !== $AUTH_PASS) {
            return response()->json(['error' => 'Invalid credentials'], 401)
                ->header('WWW-Authenticate', 'Basic realm="Tasks"');
        }
        
        return $next($request);
    }
}
