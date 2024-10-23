<?php

namespace App;

class Request
{
    /**
     * @return mixed
     */
    public function get_method(): mixed
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * @return mixed
     */
    public function get_uri(): mixed
    {
        return $_SERVER['REQUEST_URI'];
    }

    public function get_headers(): false|array
    {
        return getallheaders();
    }

    /**
     * @return array|mixed
     */
    public function get_body(): mixed
    {
        // Return JSON-decoded body content if Content-Type is JSON
        if ($this->get_header('Content-Type') === 'application/json') {
            return json_decode(file_get_contents('php://input'), true);
        }
        return $_POST;
    }

    public function get_header($header)
    {
        $headers = $this->get_headers();
        return $headers[$header] ?? null;
    }

    public function query_params(): array
    {
        return $_GET;
    }
}