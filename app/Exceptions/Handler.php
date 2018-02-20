<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {

        if ($e instanceof HttpException) {
            $content = [
                'status' => 'error',
                'type' => 'server',
                'code' => $e->getStatusCode(),
                'message' => Response::$statusTexts[$e->getStatusCode()]
            ];
        } else if ($e instanceof ValidationException) {
            $content = [
                'status' => 'error',
                'type'   => 'validation',
                'code'   => $e->getResponse()->getStatusCode(),
                'fields' => $e->validator->errors()->getMessages()
            ];
        } else {
            $content = [
                'status' => 'error',
                'type'   => 'server',
                'code'   => $e->getCode(),
                'message' => $e->getMessage()
            ];
        }

        $codes    = Response::$statusTexts;
        $httpCode = isset($codes[ $content[ 'code' ] ]) ? $content[ 'code' ] : 500;

        return new Response($content, $httpCode);
    }
}
