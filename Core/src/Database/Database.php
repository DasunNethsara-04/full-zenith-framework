<?php

namespace ZenithPHP\Core\Database;

use PDO;
use PDOException;

class Database
{
    protected static ?PDO $connection = null;

    public static function connect(): PDO
    {
        if (self::$connection === null) {
            try {
                // Load environment variables
                $host = DB_HOST;
                $dbname = DB_NAME;
                $username = DB_USER;
                $password = DB_PASS;

                $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

                // Initialize the PDO connection
                self::$connection = new PDO($dsn, $username, $password);
                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Database connection failed: " . $e->getMessage());
            }
        }
        return self::$connection;
    }

    public static function execute($sql): void
    {
        $connection = self::connect();
        $connection->exec($sql);
    }
}