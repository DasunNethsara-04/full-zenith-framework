<?php

namespace ZenithPHP\Core\Http;

use ZenithPHP\Core\Http\Request;
use ZenithPHP\Core\Http\Response;
use ReflectionMethod;

/**
 * Class Router
 *
 * Handles routing for different HTTP methods, supports dependency injection, and applies middleware.
 *
 * @package ZenithPHP\Core\Http
 */
class Router
{
    protected static array $routes = []; // Store registered routes

    /**
     * Handles HTTP requests, applies middleware, and invokes the specified controller/action.
     *
     * @param string $method The HTTP method (GET, POST, etc.)
     * @param string $path The route path
     * @param string|callable $controller The controller name or callable
     * @param string|null $action The action method to call
     * @param array $middleware Array of middleware classes to apply
     * @return mixed Response from the invoked controller/action
     */
    public static function handle($method, $path, $controller, $action = null, $middleware = [])
    {
        $currentMethod = $_SERVER['REQUEST_METHOD']; // Get the current HTTP method
        $currentUri = strtok($_SERVER['REQUEST_URI'], '?'); // Get the current URI without query string

        // Store the route with middleware
        self::$routes[] = compact('method', 'path', 'controller', 'action', 'middleware');

        // Check if the current request method matches the route method
        if ($currentMethod !== $method) {
            return false; // Method mismatch, do not process further
        }

        // Convert route path to a regex pattern
        $pattern = preg_replace('/\{(\w+)\}/', '(\d+)', $path);
        $pattern = '#^' . $pattern . '$#siD'; // Create a regex pattern for the URI

        // Check if the current URI matches the pattern
        if (preg_match($pattern, $currentUri, $matches)) {
            array_shift($matches); // Remove the first element (full match) from the matches array

            // Create a closure for the next callable in the middleware chain
            $next = function ($request, $response) use ($controller, $action, $matches) {
                // Invoke the controller action
                if (is_callable($controller)) {
                    return $controller(...$matches); // Call the controller directly
                } else {
                    // Instantiate the controller class
                    $controllerClass = 'ZenithPHP\\App\\Controllers\\' . $controller;
                    $controllerInstance = new $controllerClass;

                    // Check if the action method exists in the controller
                    if (method_exists($controllerInstance, $action)) {
                        // Use reflection to get method parameters
                        $reflection = new ReflectionMethod($controllerInstance, $action);
                        $parameters = [];

                        // Resolve parameters for the action method
                        foreach ($reflection->getParameters() as $param) {
                            $paramType = $param->getType();
                            if ($paramType && !$paramType->isBuiltin()) {
                                // If parameter is a class type
                                $className = $paramType->getName();
                                if ($className === Request::class) {
                                    $parameters[] = new Request(); // Inject Request instance
                                } elseif ($className === Response::class) {
                                    $parameters[] = new Response(); // Inject Response instance
                                } else {
                                    throw new \Exception("Cannot resolve dependency {$className}");
                                }
                            } else {
                                // Handle route parameters
                                if (!empty($matches)) {
                                    $parameters[] = array_shift($matches);
                                }
                            }
                        }

                        return $reflection->invokeArgs($controllerInstance, $parameters); // Invoke the action with parameters
                    } else {
                        echo "Error: Method '$action' not found in controller '$controllerClass'";
                    }
                }
                exit(); // Stop further execution
            };

            // Apply Middleware
            foreach ($middleware as $mw) {
                $mwInstance = new $mw();
                if (method_exists($mwInstance, 'handle') && !$mwInstance->handle(new Request(), new Response(), $next)) {
                    return false; // Middleware failed
                }
            }

            // If no middleware was applied, call $next directly
            return $next(new Request(), new Response());
        }

        return false; // No match found
    }

    // HTTP Methods

    /**
     * Register a GET route.
     */
    public static function get($path, $controller, $action = null, $middleware = [])
    {
        return self::handle('GET', $path, $controller, $action, $middleware);
    }

    /**
     * Register a POST route.
     */
    public static function post($path, $controller, $action = null, $middleware = [])
    {
        return self::handle('POST', $path, $controller, $action, $middleware);
    }

    /**
     * Register a PATCH route.
     */
    public static function patch($path, $controller, $action = null, $middleware = [])
    {
        return self::handle('PATCH', $path, $controller, $action, $middleware);
    }

    /**
     * Register a PUT route.
     */
    public static function put($path, $controller, $action = null, $middleware = [])
    {
        return self::handle('PUT', $path, $controller, $action, $middleware);
    }

    /**
     * Register a DELETE route.
     */
    public static function delete($path, $controller, $action = null, $middleware = [])
    {
        return self::handle('DELETE', $path, $controller, $action, $middleware);
    }
}
