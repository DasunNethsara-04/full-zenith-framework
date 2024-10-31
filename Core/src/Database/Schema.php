<?php

namespace ZenithPHP\Core\Database;

/**
 * Class Schema
 * 
 * Provides methods to define and manage database schemas. Supports creation and deletion of tables,
 * as well as defining various column types and primary/foreign key constraints.
 * 
 * @package ZenithPHP\Core\Database
 */
class Schema
{
    /**
     * @var string $tableName The name of the table being defined.
     */
    protected string $tableName;

    /**
     * @var array $columns Array of column definitions for the table.
     */
    protected array $columns = [];

    /**
     * @var string $primaryKey The primary key column for the table, defaulted to 'id'.
     */
    protected string $primaryKey = 'id';

    /**
     * Creates a new table and defines its structure through the provided callback.
     *
     * @param string $tableName The name of the table to create.
     * @param callable $callback The callback that defines the table schema.
     * @return void
     */
    public static function create($tableName, callable $callback): void
    {
        $schema = new self();
        $schema->tableName = $tableName;
        $callback($schema);
        $schema->executeCreate();
    }

    /**
     * Drops a table if it exists in the database.
     *
     * @param string $tableName The name of the table to drop.
     * @return void
     */
    public static function drop($tableName): void
    {
        $sql = "DROP TABLE IF EXISTS `$tableName`;";
        Database::execute($sql);
    }

    /**
     * Adds an auto-incrementing primary key column.
     *
     * @return Column The primary key column.
     */
    public function id(): Column
    {
        $column = new Column($this->primaryKey, 'INT AUTO_INCREMENT PRIMARY KEY');
        $this->columns[] = $column;
        return $column;
    }

    /**
     * Adds a string (VARCHAR) column.
     *
     * @param string $name The column name.
     * @param int $length The maximum length of the string (default is 255).
     * @return Column The created string column.
     */
    public function string(string $name, int $length = 255): Column
    {
        $column = new Column($name, "VARCHAR($length)");
        $this->columns[] = $column;
        return $column;
    }

    /**
     * Adds an integer column.
     *
     * @param string $name The column name.
     * @param int $length The length of the integer (default is 11).
     * @return Column The created integer column.
     */
    public function integer(string $name, int $length = 11): Column
    {
        $column = new Column($name, "INT($length)");
        $this->columns[] = $column;
        return $column;
    }

    /**
     * Adds a text column.
     *
     * @param string $name The column name.
     * @return Column The created text column.
     */
    public function text(string $name): Column
    {
        $column = new Column($name, "TEXT");
        $this->columns[] = $column;
        return $column;
    }

    /**
     * Adds a decimal column.
     *
     * @param string $name The column name.
     * @param int $length The total number of digits (default is 10).
     * @param int $decimals The number of decimal places (default is 2).
     * @return Column The created decimal column.
     */
    public function decimal(string $name, int $length = 10, int $decimals = 2): Column
    {
        $column = new Column($name, "DECIMAL($length, $decimals)");
        $this->columns[] = $column;
        return $column;
    }

    /**
     * Adds a boolean column (TINYINT).
     *
     * @param string $name The column name.
     * @return Column The created boolean column.
     */
    public function boolean(string $name): Column
    {
        $column = new Column($name, "TINYINT(1)");
        $this->columns[] = $column;
        return $column;
    }

    /**
     * Adds a timestamp column.
     *
     * @param string $name The column name.
     * @return Column The created timestamp column.
     */
    public function timestamp(string $name): Column
    {
        $column = new Column($name, "TIMESTAMP");
        $this->columns[] = $column;
        return $column;
    }

    /**
     * Adds a date column.
     *
     * @param string $name The column name.
     * @return Column The created date column.
     */
    public function date(string $name): Column
    {
        $column = new Column($name, "DATE");
        $this->columns[] = $column;
        return $column;
    }

    /**
     * Adds a foreign key column with an integer type.
     *
     * @param string $name The column name.
     * @return Column The created foreign ID column.
     */
    public function foreignId(string $name): Column
    {
        $column = new Column($name, "INT");
        $this->columns[] = $column;
        return $column;
    }

    /**
     * Executes the SQL command to create the table with all specified columns and constraints.
     *
     * @return void
     */
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
        echo "Executing SQL: $sql\n";
        Database::execute($sql);
    }
}
