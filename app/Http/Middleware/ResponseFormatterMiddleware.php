<?php
namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * ResponseFormatterMiddleware class
 *
 * @author Igor Manturov Jr. <igor.manturov.jr@gmail.com>
 */
class ResponseFormatterMiddleware
{

    /**
     * @param Request $request
     * @param \Closure $next
     * @return Response
     */
    public function handle(Request $request, \Closure $next)
    {
        /**
         * @var Response $response
         */
        $response = $next($request);
        return $response->isOk() ? response()->json([ 'status' => 'ok', 'data' => $response->getOriginalContent() ]) : $response;
    }
}