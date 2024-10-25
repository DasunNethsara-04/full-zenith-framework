<?php
if (php_sapi_name() === 'cli-server') {
    // Serve the requested resource as-is if it exists
    $filePath = __DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    if (file_exists($filePath) && !is_dir($filePath)) {
        return false;
    }
}
require 'index.php';