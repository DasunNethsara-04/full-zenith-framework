<?php

namespace App;

class Router
{
    public static function handle($method = 'GET', $path = '/', $controller = '', $action = null)
    {
        $currentMethod = $_SERVER['REQUEST_METHOD'];
        $currentUri = strtok($_SERVER['REQUEST_URI'], '?'); // Remove query string from URI

        // Check if the request method matches
        if ($currentMethod !== $method) {
            return false;
        }

        // Convert route path to a regex pattern for dynamic segments (e.g., {id})
        $pattern = preg_replace('/\{(\w+)\}/', '(\d+)', $path); // Match numeric IDs for simplicity
        $pattern = '#^' . $pattern . '$#siD';

        // Match the current URI against the route pattern
        if (preg_match($pattern, $currentUri, $matches)) {
            array_shift($matches); // Remove the full match (we only need parameters)

            if (is_callable($controller)) {
                $controller(...$matches);
            } else {
                // Use the fully qualified namespace for the controller
                $controllerClass = 'Controller\\' . $controller;
                $controllerInstance = new $controllerClass;

                if (method_exists($controllerInstance, $action)) {
                    // Pass dynamic parameters to the controller method
                    $controllerInstance->$action(...$matches);
                } else {
                    // Handle case where method doesn't exist
                    echo "Error: Method '$action' not found in controller '$controllerClass'";
                }
            }
            exit();
        }

        return false;
    }

    public static function get($path = '/', $controller = '', $action = null): ?false
    {
        return self::handle('GET', $path, $controller, $action);
    }

    public static function post($path = '/', $controller = '', $action = null): ?false
    {
        return self::handle('POST', $path, $controller, $action);
    }

    public static function patch($path = '/', $controller = '', $action = null): ?false
    {
        return self::handle('PATCH', $path, $controller, $action);
    }

    public static function put($path = '/', $controller = '', $action = null): ?false
    {
        return self::handle('PUT', $path, $controller, $action);
    }

    public static function delete($path = '/', $controller = '', $action = null): ?false
    {
        return self::handle('DELETE', $path, $controller, $action);
    }
}
