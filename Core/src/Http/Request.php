<?php

namespace ZenithPHP\Core\Http;

/**
 * Class Request
 *
 * Represents the HTTP request, providing methods to access HTTP method, URI, headers, body content, and query parameters.
 * 
 * @package ZenithPHP\Core\Http
 */
class Request
{
    /**
     * Retrieves the HTTP method (e.g., GET, POST) of the current request.
     *
     * @return mixed HTTP request method.
     */
    public function get_method(): mixed
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * Retrieves the URI of the current request.
     *
     * @return mixed The URI of the request.
     */
    public function get_uri(): mixed
    {
        return $_SERVER['REQUEST_URI'];
    }

    /**
     * Retrieves all headers of the current request.
     *
     * @return false|array An array of headers or false on failure.
     */
    public function get_headers(): false|array
    {
        return getallheaders();
    }

    /**
     * Retrieves the body content of the request. 
     * If the Content-Type is JSON, it returns the JSON-decoded data; otherwise, it returns the POST data.
     *
     * @return array|mixed The request body data.
     */
    public function get_body(): mixed
    {
        if ($this->get_header('Content-Type') === 'application/json') {
            return json_decode(file_get_contents('php://input'), true);
        }
        return $_POST;
    }

    /**
     * Retrieves the value of a specific header from the request.
     *
     * @param string $header The name of the header.
     * @return mixed|null The header value, or null if not found.
     */
    public function get_header($header)
    {
        $headers = $this->get_headers();
        return $headers[$header] ?? null;
    }

    /**
     * Retrieves query parameters from the URL of the current request.
     *
     * @return array The array of query parameters.
     */
    public function query_params(): array
    {
        return $_GET;
    }
}
