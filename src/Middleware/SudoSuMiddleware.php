<?php

namespace VIACreative\SudoSu\Middleware;

use Closure;
use Exception;
use VIACreative\SudoSu\SudoSu;

class SudoSuMiddleware
{
    protected $sudoSu;

    public function __construct(SudoSu $sudoSu)
    {
        $this->sudoSu = $sudoSu;
    }

    public function handle($request, Closure $next)
    {
        try {
            $response = $next($request);
        } catch (Exception $e) {
            $this->handleException($request, $e);
        }
        
        if (!$request->expectsJson()) {
            $this->sudoSu->injectToView($response);
        }

        return $response;
    }

    // Copied from Illuminate\Routing\Pipeline::handleException()
    protected function handleException($passable, Exception $e)
    {
        if (! $this->container->bound(ExceptionHandler::class) || ! $passable instanceof Request) {
            throw $e;
        }

        $handler = $this->container->make(ExceptionHandler::class);

        $handler->report($e);

        $response = $handler->render($passable, $e);

        if (method_exists($response, 'withException')) {
            $response->withException($e);
        }

        return $response;
    }
}
