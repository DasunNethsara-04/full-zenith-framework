<?php

namespace Controller;

use PDO;

abstract class Controller
{
    protected PDO $pdo;

    public function __construct()
    {
        $this->pdo = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME.";", DB_USER, DB_PASS);
    }

    protected function view($filename = '', $data = []): void
    {
        require_once __DIR__ . '/../View/' . $filename . '.php';
    }

}