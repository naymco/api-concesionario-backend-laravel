<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use JWTAuth;
use Exception;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JwtMiddleware extends BaseMiddleware
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
            $user = JWTAuth::parseToken()->authenticate();
        }catch (Exception $error)
        {
            if( $error instanceof TokenInvalidException)
            {
                return response()->json([
                    'code' => $error,
                    'status' => 404,
                    'message' => 'Token inválido'
                ], 404);
            } elseif ($error instanceof TokenExpiredException)
            {
                return response()->json([
                    'code' => $error,
                    'status' => 404,
                    'message' => 'Token expirado'
                ], 403);
            } else{
                return response()->json([
                    'code' => 'error',
                    'status' => 404,
                    'message' => 'Autorización del token no funciona'
                ], 500);
            }
        }

        return $next($request);
    }
}
