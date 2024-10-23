<?php

namespace Model;

use PDO;

abstract class Model
{
    private PDO $pdo;
    protected string $table_name = "";

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll(): false|array
    {
        $stmt = $this->pdo->query("SELECT * FROM $this->table_name");
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM $this->table_name WHERE id=?");
        $stmt->bindValue(1, $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

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

    public function update(int|string $id, array $data): void
    {
        // Prepare SET clause dynamically
        $setClause = implode(', ', array_map(function ($key) {
            return "$key = ?";
        }, array_keys($data)));

        // Prepare the SQL query
        $sql = "UPDATE $this->table_name SET $setClause WHERE id = ?";

        $stmt = $this->pdo->prepare($sql);

        // Bind the data values
        $i = 1;
        foreach ($data as $value) {
            $stmt->bindValue($i, $value);
            $i++;
        }

        // Bind the ID at the end
        $stmt->bindValue($i, $id);

        $stmt->execute();
    }

    public function delete(int|string $id): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM $this->table_name WHERE id = ?");
        $stmt->bindValue(1, $id);
        $stmt->execute();
    }
}