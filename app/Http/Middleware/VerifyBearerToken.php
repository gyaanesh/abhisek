<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifyBearerToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // return $next($request);
        $expectedBearerToken = 'V2r$8Fy&nGh@p5Qw';
        
        $bearerToken = $request->header('app_authorization');
        if (!$bearerToken || !str_contains($bearerToken, $expectedBearerToken)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
    }

