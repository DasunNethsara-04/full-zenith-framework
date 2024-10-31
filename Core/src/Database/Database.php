<?php

namespace ZenithPHP\Core\Database;

use PDO;
use PDOException;

/**
 * Class for managing the database connection and executing SQL statements.
 * 
 * @package ZenithPHP\Core\Database
 */
class Database
{
    /**
     * Holds the singleton PDO connection instance.
     * 
     * @var PDO|null
     */
    protected static ?PDO $connection = null;

    /**
     * Establishes a PDO connection if one doesn't already exist.
     * 
     * @return PDO The PDO connection instance.
     */
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

    /**
     * Executes a given SQL statement using the established PDO connection.
     * 
     * @param string $sql The SQL statement to execute.
     * 
     * @return void
     */
    public static function execute($sql): void
    {
        $connection = self::connect();
        $connection->exec($sql);
    }
}
