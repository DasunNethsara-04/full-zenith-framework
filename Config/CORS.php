<?php

namespace Config;

class CORS
{
    public static function setHeaders()
    {
        // Set allowed origins
        header("Access-Control-Allow-Origin: *"); // Change "*" to specific domains if needed

        // Set allowed HTTP methods
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");

        // Set allowed headers
        header("Access-Control-Allow-Headers: Content-Type, Authorization");

        // Handle preflight requests (OPTIONS method)
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            header("HTTP/1.1 200 OK");
            exit();
        }
    }
}