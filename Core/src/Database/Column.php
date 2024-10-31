<?php

namespace ZenithPHP\Core\Database;

/**
 * Class representing a database column with various attribute modifiers.
 * 
 * @package ZenithPHP\Core\Database
 */
class Column
{
    /**
     * The name of the column.
     * 
     * @var string
     */
    protected string $name;

    /**
     * The data type of the column.
     * 
     * @var string
     */
    protected string $type;

    /**
     * The list of modifiers for the column (e.g., UNIQUE, NOT NULL).
     * 
     * @var array
     */
    protected array $modifiers = [];

    /**
     * Indicates whether the column has a foreign key constraint.
     * 
     * @var bool
     */
    protected bool $hasForeignKey = false;

    /**
     * Constructor for the Column class.
     * 
     * @param string $name The name of the column.
     * @param string $type The data type of the column.
     */
    public function __construct(string $name, string $type)
    {
        $this->name = $name;
        $this->type = $type;
    }

    /**
     * Sets the column as unique.
     * 
     * @return self
     */
    public function unique(): self
    {
        $this->modifiers[] = 'UNIQUE';
        return $this;
    }

    /**
     * Sets a default value for the column.
     * 
     * @param mixed $value The default value.
     * 
     * @return self
     */
    public function default($value): self
    {
        $this->modifiers[] = "DEFAULT $value";
        return $this;
    }

    /**
     * Allows the column to be nullable.
     * 
     * @return self
     */
    public function nullable(): self
    {
        $this->modifiers[] = 'NULL';
        return $this;
    }

    /**
     * Sets the column as not nullable.
     * 
     * @return self
     */
    public function notNullable(): self
    {
        $this->modifiers[] = 'NOT NULL';
        return $this;
    }

    /**
     * Sets a foreign key constraint for the column referencing another table.
     * 
     * @param string $table The referenced table name.
     * 
     * @return self
     */
    public function constrained(string $table): self
    {
        $this->modifiers[] = ", FOREIGN KEY (`{$this->name}`) REFERENCES `$table` (`id`)";
        return $this;
    }

    /**
     * Adds ON DELETE CASCADE to the foreign key constraint if present.
     * 
     * @return self
     */
    public function cascadeOnDelete(): self
    {
        if ($this->hasForeignKey) {
            $this->modifiers[] = 'ON DELETE CASCADE';
        }
        return $this;
    }

    /**
     * Adds ON UPDATE CASCADE to the foreign key constraint if present.
     * 
     * @return self
     */
    public function cascadeOnUpdate(): self
    {
        if ($this->hasForeignKey) {
            $this->modifiers[] = 'ON UPDATE CASCADE';
        }
        return $this;
    }

    /**
     * Converts the column definition to a string for SQL execution.
     * 
     * @return string
     */
    public function __toString(): string
    {
        return "`{$this->name}` {$this->type} " . implode(' ', $this->modifiers);
    }
}
