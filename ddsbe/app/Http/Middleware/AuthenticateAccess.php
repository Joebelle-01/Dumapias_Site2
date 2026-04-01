<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;

class AuthenticateAccess
{
    public function handle($request, Closure $next)
    {
        $accepted = (string) env('ACCEPTED_SECRETS', '');
        $validSecrets = array_filter(array_map('trim', explode(',', $accepted)));

        $provided = (string) $request->header('Authorization', '');

        if ($provided !== '' && in_array($provided, $validSecrets, true)) {
            return $next($request);
        }

        abort(Response::HTTP_UNAUTHORIZED);
    }
}
