<?php

namespace ZenithPHP\Core\Http;

/**
 * Class Response
 *
 * Handles HTTP responses, providing methods to set status codes, headers, and send JSON or plain content responses.
 * 
 * @package ZenithPHP\Core\Http
 */
class Response
{
    /**
     * Sends a JSON response with a given data payload and optional status code.
     *
     * @param mixed $data The data to be JSON-encoded and sent in the response.
     * @param int $statusCode The HTTP status code for the response (default: 200).
     * @return void
     */
    public function json($data, $statusCode = 200): void
    {
        header('Content-Type: application/json');
        http_response_code($statusCode);
        echo json_encode($data);
        exit();
    }

    /**
     * Sets the HTTP status code for the response.
     *
     * @param int $statusCode The HTTP status code to set.
     * @return void
     */
    public function setStatusCode($statusCode): void
    {
        http_response_code($statusCode);
    }

    /**
     * Sets a specific header for the HTTP response.
     *
     * @param string $name The name of the header.
     * @param string $value The value for the header.
     * @return void
     */
    public function setHeader($name, $value): void
    {
        header("$name: $value");
    }

    /**
     * Sends a plain text or HTML content response with an optional status code.
     *
     * @param string $content The content to be sent in the response body.
     * @param int $statusCode The HTTP status code for the response (default: 200).
     * @return void
     */
    public function send($content, $statusCode = 200): void
    {
        http_response_code($statusCode);
        echo $content;
        exit();
    }
}
