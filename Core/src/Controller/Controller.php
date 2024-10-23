<?php

namespace ZenithPHP\Core\Controller;

use PDO;

abstract class Controller
{
    protected PDO $pdo;

    public function __construct()
    {
        $this->pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";", DB_USER, DB_PASS);
    }

    protected function view($filename = '', $data = []): void
    {
        // Assuming the project root is one level above 'Core/src/Controller'
        $baseDir = dirname(__DIR__, 3); // This goes 3 levels up to the project root
        require_once $baseDir . '/View/' . $filename . '.php';
    }
}