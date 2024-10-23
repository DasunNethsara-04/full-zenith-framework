<?php

namespace App\Exceptions;

class ExceptionHandler
{
    public static function handle(\Exception $exception)
    {
        http_response_code($exception->getCode());

        if (defined('API')) {
            // Handle API response
            echo json_encode([
                'error' => [
                    'code' => $exception->getCode(),
                    'message' => $exception->getMessage()
                ]
            ]);
        } else {
            // Handle web response (e.g., show a custom error page)
            echo "<h1>Error {$exception->getCode()}</h1>";
            echo "<p>{$exception->getMessage()}</p>";
        }

        exit();
    }
}
