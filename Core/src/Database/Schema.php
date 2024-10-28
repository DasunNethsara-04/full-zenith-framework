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

    public function id(): Column
    {
        $column = new Column($this->primaryKey, 'INT AUTO_INCREMENT PRIMARY KEY');
        $this->columns[] = $column;
        return $column;
    }

    public function string(string $name, int $length = 255): Column
    {
        $column = new Column($name, "VARCHAR($length)");
        $this->columns[] = $column;
        return $column;
    }

    public function integer(string $name, int $length = 11): Column
    {
        $column = new Column($name, "INT($length)");
        $this->columns[] = $column;
        return $column;
    }

    public function text(string $name): Column
    {
        $column = new Column($name, "TEXT");
        $this->columns[] = $column;
        return $column;
    }

    public function decimal(string $name, int $length = 10, int $decimals = 2): Column
    {
        $column = new Column($name, "DECIMAL($length, $decimals)");
        $this->columns[] = $column;
        return $column;
    }

    public function boolean(string $name): Column
    {
        $column = new Column($name, "TINYINT(1)");
        $this->columns[] = $column;
        return $column;
    }

    public function timestamp(string $name): Column
    {
        $column = new Column($name, "TIMESTAMP");
        $this->columns[] = $column;
        return $column;
    }

    public function date(string $name): Column
    {
        $column = new Column($name, "DATE");
        $this->columns[] = $column;
        return $column;
    }

    public function foreignId(string $name): Column
    {
        $column = new Column($name, "INT");
        $this->columns[] = $column;
        return $column;
    }

    protected function executeCreate(): void
    {
        $columnsSql = [];
        $constraints = [];

        foreach ($this->columns as $column) {
            $columnDefinition = (string)$column;

            // Separate foreign key constraints into the $constraints array
            if (str_contains($columnDefinition, 'FOREIGN KEY')) {
                $constraints[] = $columnDefinition;
            } else {
                $columnsSql[] = $columnDefinition;
            }
        }

        // Construct the SQL statement
        $sql = "CREATE TABLE IF NOT EXISTS `{$this->tableName}` (" . implode(", ", $columnsSql);

        // Add foreign key constraints at the end of the column definitions
        if (!empty($constraints)) {
            $sql .= ", " . implode(", ", $constraints);
        }

        $sql .= ");";

        // Debugging output
        echo "Executing SQL: $sql\n"; // Add this line to print the SQL
        Database::execute($sql);
    }
}
