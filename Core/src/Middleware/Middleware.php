<?php

namespace ZenithPHP\Core\Middleware;

use ZenithPHP\Core\Http\Request;
use ZenithPHP\Core\Http\Response;

abstract class Middleware
{
    /**
     * Handle method that each middleware should implement.
     *
     * @param Request $request
     * @param Response $response
     * @param callable $next The next middleware or controller action.
     */
    abstract public function handle(Request $request, Response $response, callable $next);
}
