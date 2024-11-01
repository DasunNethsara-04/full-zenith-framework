<?php

namespace ZenithPHP\Core\Http;

use ZenithPHP\Core\Http\Request;
use ZenithPHP\Core\Http\Response;
use ReflectionMethod;

/**
 * Class Router
 *
 * Handles routing for various HTTP methods, supports dependency injection for controller methods,
 * and applies middleware to specified routes.
 *
 * @package ZenithPHP\Core\Http
 */
class Router
{
    /**
     * @var array Stores registered routes, including HTTP method, path, controller, action, and middleware.
     */
    protected static array $routes = [];

    /**
     * Matches a request with the specified HTTP method and URI to a registered route, applies middleware,
     * injects dependencies, and invokes the specified controller and action.
     *
     * @param string $method The HTTP method for the route (e.g., GET, POST).
     * @param string $path The URI pattern to match the route.
     * @param string|callable $controller The controller class or callable function handling the request.
     * @param string|null $action The method within the controller to invoke.
     * @param array $middleware An array of middleware classes to apply to the route.
     * @return bool Returns false if the method or URI does not match, otherwise it invokes the matched action.
     * @throws \Exception If a dependency cannot be resolved for injection.
     */
    public static function handle($method, $path, $controller, $action = null, $middleware = [])
    {
        $currentMethod = $_SERVER['REQUEST_METHOD'];
        $currentUri = strtok($_SERVER['REQUEST_URI'], '?');

        // Store the route with middleware
        self::$routes[] = compact('method', 'path', 'controller', 'action', 'middleware');

        if ($currentMethod !== $method) {
            return false;
        }

        $pattern = preg_replace('/\{(\w+)\}/', '(\d+)', $path); // Convert path to regex
        $pattern = '#^' . $pattern . '$#siD';

        if (preg_match($pattern, $currentUri, $matches)) {
            array_shift($matches);

            // Create instances of Request and Response
            $request = new Request();
            $response = new Response();

            // Apply Middleware
            foreach ($middleware as $mw) {
                $mwInstance = new $mw();
                if (method_exists($mwInstance, 'handle')) {
                    // Pass request, response, and the next closure
                    $next = function ($req, $res) use ($controller, $action, $matches) {
                        $controllerClass = 'ZenithPHP\\App\\Controllers\\' . $controller;
                        $controllerInstance = new $controllerClass;

                        // Prepare parameters for the controller action
                        $parameters = [];

                        if (method_exists($controllerInstance, $action)) {
                            $reflection = new ReflectionMethod($controllerInstance, $action);
                            foreach ($reflection->getParameters() as $param) {
                                $paramType = $param->getType();
                                if ($paramType && !$paramType->isBuiltin()) {
                                    $className = $paramType->getName();
                                    if ($className === Request::class) {
                                        $parameters[] = $req; // Add the Request object
                                    } elseif ($className === Response::class) {
                                        $parameters[] = $res; // Add the Response object
                                    } else {
                                        throw new \Exception("Cannot resolve dependency {$className}");
                                    }
                                } else {
                                    if (!empty($matches)) {
                                        $parameters[] = array_shift($matches);
                                    }
                                }
                            }
                            // Invoke the controller action with parameters
                            return $reflection->invokeArgs($controllerInstance, $parameters);
                        } else {
                            echo "Error: Method '$action' not found in controller '$controllerClass'";
                        }
                        exit();
                    };

                    // Call the middleware handle method
                    $result = $mwInstance->handle($request, $response, $next);
                    if ($result === false) {
                        return; // Middleware failed, stop processing
                    }
                }
            }

            // If all middleware passes, call the action directly
            return $next($request, $response);
        }

        return false; // No route matched
    }

    /**
     * Registers a GET route.
     *
     * @param string $path The URI pattern to match the route.
     * @param string|callable $controller The controller class or callable function handling the request.
     * @param string|null $action The method within the controller to invoke.
     * @param array $middleware An array of middleware classes to apply to the route.
     */
    public static function get($path, $controller, $action = null, $middleware = [])
    {
        return self::handle('GET', $path, $controller, $action, $middleware);
    }

    /**
     * Registers a POST route.
     *
     * @param string $path The URI pattern to match the route.
     * @param string|callable $controller The controller class or callable function handling the request.
     * @param string|null $action The method within the controller to invoke.
     * @param array $middleware An array of middleware classes to apply to the route.
     */
    public static function post($path, $controller, $action = null, $middleware = [])
    {
        return self::handle('POST', $path, $controller, $action, $middleware);
    }

    /**
     * Registers a PATCH route.
     *
     * @param string $path The URI pattern to match the route.
     * @param string|callable $controller The controller class or callable function handling the request.
     * @param string|null $action The method within the controller to invoke.
     * @param array $middleware An array of middleware classes to apply to the route.
     */
    public static function patch($path, $controller, $action = null, $middleware = [])
    {
        return self::handle('PATCH', $path, $controller, $action, $middleware);
    }

    /**
     * Registers a PUT route.
     *
     * @param string $path The URI pattern to match the route.
     * @param string|callable $controller The controller class or callable function handling the request.
     * @param string|null $action The method within the controller to invoke.
     * @param array $middleware An array of middleware classes to apply to the route.
     */
    public static function put($path, $controller, $action = null, $middleware = [])
    {
        return self::handle('PUT', $path, $controller, $action, $middleware);
    }

    /**
     * Registers a DELETE route.
     *
     * @param string $path The URI pattern to match the route.
     * @param string|callable $controller The controller class or callable function handling the request.
     * @param string|null $action The method within the controller to invoke.
     * @param array $middleware An array of middleware classes to apply to the route.
     */
    public static function delete($path, $controller, $action = null, $middleware = [])
    {
        return self::handle('DELETE', $path, $controller, $action, $middleware);
    }
}
