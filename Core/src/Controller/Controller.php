<?php

namespace ZenithPHP\Core\Controller;

use PDO;

/**
 * Abstract base Controller class to provide core functionalities for all controllers.
 * 
 * @package ZenithPHP\Core\Controller
 */
abstract class Controller
{
    /**
     * Database connection instance.
     * 
     * @var PDO
     */
    protected PDO $pdo;

    /**
     * Initializes the Controller and sets up the PDO database connection.
     */
    public function __construct()
    {
        $this->pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";", DB_USER, DB_PASS);
    }

    /**
     * Renders a view file and passes data to it.
     * 
     * @param string $filename The name of the view file (without the `.php` extension).
     * @param array $data An associative array of data to pass to the view.
     * 
     * @return void
     */
    protected function view($filename = '', $data = []): void
    {
        $baseDir = dirname(__DIR__, 3);
        require_once $baseDir . '/View/' . $filename . '.php';
    }
}
