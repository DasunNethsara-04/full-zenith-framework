<?php

namespace ZenithPHP\Core\Http;

use ZenithPHP\Core\Http\Request;
use ZenithPHP\Core\Http\Response;
use ReflectionMethod;

/**
 * Class Router
 *
 * This Router class handles routing for different HTTP methods and performs dynamic dependency injection
 * for controllers' method parameters. It supports route patterns with dynamic parameters (e.g., {id}) 
 * and injects `Request` and `Response` objects as dependencies.
 *
 * @package ZenithPHP\Core\Http
 */
class Router
{
    /**
     * Handles HTTP requests by matching method and URI to specified routes, injecting dependencies, and
     * invoking the specified controller and action.
     *
     * @param string $method HTTP method (e.g., GET, POST).
     * @param string $path URI pattern to match the route.
     * @param string|callable $controller Controller class or callable function for handling the request.
     * @param string|null $action Method name within the controller class.
     * @return bool|void False if the method or URI doesn't match; otherwise, invokes the matched action.
     * @throws \Exception If unable to resolve a dependency type for injection.
     */
    public static function handle($method = 'GET', $path = '/', $controller = '', $action = null)
    {
        $currentMethod = $_SERVER['REQUEST_METHOD'];
        $currentUri = strtok($_SERVER['REQUEST_URI'], '?'); // Remove query string

        if ($currentMethod !== $method) {
            return false;
        }

        $pattern = preg_replace('/\{(\w+)\}/', '(\d+)', $path); // Convert route path to regex
        $pattern = '#^' . $pattern . '$#siD';

        if (preg_match($pattern, $currentUri, $matches)) {
            array_shift($matches);

            if (is_callable($controller)) {
                $controller(...$matches);
            } else {
                $controllerClass = 'ZenithPHP\\App\\Controllers\\' . $controller;
                $controllerInstance = new $controllerClass;

                if (method_exists($controllerInstance, $action)) {
                    $reflection = new ReflectionMethod($controllerInstance, $action);
                    $parameters = [];

                    foreach ($reflection->getParameters() as $param) {
                        $paramType = $param->getType();

                        if ($paramType && !$paramType->isBuiltin()) {
                            $className = $paramType->getName();
                            if ($className === Request::class) {
                                $parameters[] = new Request();
                            } elseif ($className === Response::class) {
                                $parameters[] = new Response();
                            } else {
                                throw new \Exception("Cannot resolve dependency {$className}");
                            }
                        } else {
                            if (!empty($matches)) {
                                $parameters[] = array_shift($matches);
                            }
                        }
                    }

                    $reflection->invokeArgs($controllerInstance, $parameters);
                } else {
                    echo "Error: Method '$action' not found in controller '$controllerClass'";
                }
            }
            exit();
        }

        return false;
    }

    /**
     * Registers a GET route.
     */
    public static function get($path = '/', $controller = '', $action = null): ?false
    {
        return self::handle('GET', $path, $controller, $action);
    }

    /**
     * Registers a POST route.
     */
    public static function post($path = '/', $controller = '', $action = null): ?false
    {
        return self::handle('POST', $path, $controller, $action);
    }

    /**
     * Registers a PATCH route.
     */
    public static function patch($path = '/', $controller = '', $action = null): ?false
    {
        return self::handle('PATCH', $path, $controller, $action);
    }

    /**
     * Registers a PUT route.
     */
    public static function put($path = '/', $controller = '', $action = null): ?false
    {
        return self::handle('PUT', $path, $controller, $action);
    }

    /**
     * Registers a DELETE route.
     */
    public static function delete($path = '/', $controller = '', $action = null): ?false
    {
        return self::handle('DELETE', $path, $controller, $action);
    }
}
