<?php

namespace ZenithPHP\Config;

/**
 * Class CORS
 *
 * The CORS class handles Cross-Origin Resource Sharing (CORS) settings for the application.
 * It provides methods to set the appropriate HTTP headers to control resource sharing across 
 * different domains and manage preflight requests.
 *
 * @package ZenithPHP\Config
 */
class CORS
{
    /**
     * Sets the necessary headers for CORS.
     *
     * This method configures the allowed origins, HTTP methods, and headers for cross-origin 
     * requests. It also handles preflight requests by responding to OPTIONS requests.
     *
     * Note: The allowed origins can be restricted to specific domains for security purposes.
     */
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
