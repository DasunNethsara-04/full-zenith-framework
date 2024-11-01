<?php

namespace ZenithPHP\App\Middleware;

use ZenithPHP\Core\Http\Request;
use ZenithPHP\Core\Http\Response;
use ZenithPHP\Core\Middleware\Middleware;

class AuthMiddleware extends Middleware
{
    protected string $role;

    public function __construct(string $role = '')
    {
        $this->role = $role;
    }

    public function handle(Request $request, Response $response, callable $next)
    {
        session_start();
        $user = $_SESSION['user'] ?? null;

        if ($this->role && (!$user || $user['role'] !== $this->role)) {
            $response->send("Unauthorized", 403);
            exit();
        }

        if (!$user && $this->role === '') {
            $response->send("Please login", 401);
            exit();
        }

        // Call the next middleware or controller action
        return $next($request, $response);
    }
}