<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Exception;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use JWTAuth;

class AuthLog extends BaseMiddleware
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
        try {
            JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return response()->json(['code' => 'token-invalid', 'message' => 'Esta Sessão é Inválida! Faça Login novamente para continuar!'], 401);
            } elseif ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return response()->json(['code' => 'token-expired', 'message' => 'A sessão expirou. Faça Login novamente para continuar!'], 401);
            } else {
                return response()->json(['message' => 'Autorização de sessão não encontrada. Faça Login novamente para continuar!'], 401);
            }
        }
        return $next($request);
    }
}
