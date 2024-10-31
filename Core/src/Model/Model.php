<?php

namespace ZenithPHP\Core\Model;

use PDO;

/**
 * Class Model
 *
 * The abstract Model class provides a base for database interactions in the application. It includes 
 * methods for basic CRUD operations (create, read, update, delete) and is designed to be extended 
 * by specific models representing individual database tables.
 *
 * @package ZenithPHP\Core\Model
 */
abstract class Model
{
    /** @var PDO $pdo The PDO database connection instance. */
    private PDO $pdo;

    /** @var string $table_name The name of the database table. */
    protected string $table_name = "";

    /**
     * Model constructor.
     *
     * @param PDO $pdo The PDO instance for database connection.
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Retrieves all records from the database table.
     *
     * @return false|array An array of all records as objects or false on failure.
     */
    public function getAll(): false|array
    {
        $stmt = $this->pdo->query("SELECT * FROM $this->table_name");
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Retrieves a single record by ID.
     *
     * @param int|string $id The ID of the record to retrieve.
     * @return mixed The record as an object or false if not found.
     */
    public function getById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM $this->table_name WHERE id=?");
        $stmt->bindValue(1, $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Inserts a new record into the database table.
     *
     * @param array $data An associative array of column-value pairs to insert.
     */
    public function store(array $data): void
    {
        $stmt = $this->pdo->prepare("INSERT INTO $this->table_name (" . implode(',', array_keys($data)) . ") VALUES (" . implode(',', array_fill(0, count($data), '?')) . ")");
        $i = 1;
        foreach ($data as $key => $value) {
            $stmt->bindValue($i, $value);
            $i++;
        }
        $stmt->execute();
    }

    /**
     * Updates an existing record by ID.
     *
     * @param int|string $id The ID of the record to update.
     * @param array $data An associative array of column-value pairs to update.
     */
    public function update(int|string $id, array $data): void
    {
        $setClause = implode(', ', array_map(function ($key) {
            return "$key = ?";
        }, array_keys($data)));

        $sql = "UPDATE $this->table_name SET $setClause WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);

        $i = 1;
        foreach ($data as $value) {
            $stmt->bindValue($i, $value);
            $i++;
        }

        $stmt->bindValue($i, $id);
        $stmt->execute();
    }

    /**
     * Deletes a record from the database table by ID.
     *
     * @param int|string $id The ID of the record to delete.
     */
    public function delete(int|string $id): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM $this->table_name WHERE id = ?");
        $stmt->bindValue(1, $id);
        $stmt->execute();
    }
}
