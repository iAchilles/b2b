<?php
namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Http\Response;


/**
 * JsonMiddleware class
 *
 * @author Igor Manturov Jr. <igor.manturov.jr@gmail.com>
 */
class JsonMiddleware
{

    public function handle(Request $request, \Closure $next)
    {
        if (!$request->isJson()) {
            $content = [
                'status' => 'error',
                'error'  => [
                    'type'    => 'server',
                    'code'    => Response::HTTP_BAD_REQUEST,
                    'message' => Response::$statusTexts[ Response::HTTP_BAD_REQUEST]
                ]
            ];
            return new Response($content, Response::HTTP_BAD_REQUEST);
        }
        return $next($request);
    }
}
