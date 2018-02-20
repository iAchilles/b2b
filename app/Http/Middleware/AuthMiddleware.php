<?php
namespace App\Http\Middleware;

use App\Services\Security\TokenService;
use Illuminate\Http\Request;

/**
 * AuthMiddleware class
 *
 * @author Igor Manturov Jr. <igor.manturov.jr@gmail.com>
 */
class AuthMiddleware
{

    /**
     * @param Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, \Closure $next)
    {
        if (is_null($request->bearerToken())) {
            abort(401);
        }

        $tokenString = $request->bearerToken();
        $token       = TokenService::fromString($tokenString);

        if (is_null($token)) {
            abort(401);
        }

        $request->attributes->add([ 'token' => $token ]);

        return $next($request);
    }
}
