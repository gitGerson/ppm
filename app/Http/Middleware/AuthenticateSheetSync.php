<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateSheetSync
{
    public function handle(Request $request, Closure $next): Response
    {
        $request->headers->set('Accept', 'application/json');
        $token = (string) config('santri_sheet.api_token');

        abort_unless(config('santri_sheet.api_enabled'), 404);
        abort_unless(strlen($token) >= 32 && hash_equals($token, $request->bearerToken() ?? ''), 401);

        return $next($request)->header('Cache-Control', 'no-store, private');
    }
}
