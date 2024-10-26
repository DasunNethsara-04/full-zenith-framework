<?php

namespace ZenithPHP\Core\Database;

class Schema
{
    protected string $tableName;
    protected array $columns = [];
    protected string $primaryKey = 'id';

    public static function create($tableName, callable $callback): void
    {
        $schema = new self();
        $schema->tableName = $tableName;
        $callback($schema);
        $schema->executeCreate();
    }

    public static function drop($tableName): void
    {
        $sql = "DROP TABLE IF EXISTS `$tableName`;";
        Database::execute($sql);
    }

    public function id(): void
    {
        $this->columns[] = "`{$this->primaryKey}` INT AUTO_INCREMENT PRIMARY KEY";
    }

    public function string($name, $length = 255): void
    {
        $this->columns[] = "`$name` VARCHAR($length)";
    }

    public function integer($name): void
    {
        $this->columns[] = "`$name` INT";
    }

    public function timestamps(): void
    {
        $this->columns[] = "`created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP";
        $this->columns[] = "`updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP";
    }

    protected function executeCreate(): void
    {
        $columnsSql = implode(", ", $this->columns);
        $sql = "CREATE TABLE IF NOT EXISTS `{$this->tableName}` ({$columnsSql});";
        Database::execute($sql);
    }
}
