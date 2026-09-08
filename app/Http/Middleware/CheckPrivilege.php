<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPrivilege
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $privilege):Response
    {
        if (!$request->user() || !$request->user()->hasPrivilege($privilege)) {
            return response()->json([
                'message' => 'Forbidden: You do not have the required privilege to access this resource.',
            ], 403);
        }

        return $next($request);
    }
}
