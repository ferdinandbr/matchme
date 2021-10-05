<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifyGroups
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        $check = auth()->user()->group->group_id;

        if ($check == 1) {
            return $response;
        } else {
            return response()->json(['message' => 'Não autorizado'], 409);
        }

    }
}
