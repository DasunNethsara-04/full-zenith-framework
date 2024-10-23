<?php

namespace App;

class Response
{
    public function json($data, $statusCode = 200): void
    {
        header('Content-Type: application/json');
        http_response_code($statusCode);
        echo json_encode($data);
        exit();
    }

    public function setStatusCode($statusCode): void
    {
        http_response_code($statusCode);
    }

    public function setHeader($name, $value): void
    {
        header("$name: $value");
    }

    public function send($content, $statusCode = 200): void
    {
        http_response_code($statusCode);
        echo $content;
        exit();
    }
}
