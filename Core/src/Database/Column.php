<?php

namespace ZenithPHP\Core\Database;

class Column
{
    protected string $name;
    protected string $type;
    protected array $modifiers = [];

    public function __construct(string $name, string $type)
    {
        $this->name = $name;
        $this->type = $type;
    }

    public function unique(): self
    {
        $this->modifiers[] = 'UNIQUE';
        return $this;
    }

    public function default($value): self
    {
        $this->modifiers[] = "DEFAULT $value";
        return $this;
    }

    public function nullable(): self
    {
        $this->modifiers[] = 'NULL';
        return $this;
    }

    public function notNullable(): self
    {
        $this->modifiers[] = 'NOT NULL';
        return $this;
    }

    public function constrained(string $table): self
    {
        $this->modifiers[] = "REFERENCES `$table` (`id`)";
        return $this;
    }

    public function cascadeOnDelete(): self
    {
        $this->modifiers[] = 'ON DELETE CASCADE';
        return $this;
    }

    public function cascadeOnUpdate(): self
    {
        $this->modifiers[] = 'ON UPDATE CASCADE';
        return $this;
    }

    public function __toString(): string
    {
        $modifiers = $this->modifiers;

        // Check if the column has a REFERENCES constraint before adding ON DELETE or ON UPDATE
        if (!in_array('REFERENCES', array_map(fn($mod) => strtok($mod, ' '), $modifiers))) {
            // Exclude cascade if it's not a foreign key constraint
            $modifiers = array_filter($modifiers, fn($mod) => !str_contains($mod, 'ON DELETE') && !str_contains($mod, 'ON UPDATE'));
        }
        return "`{$this->name}` {$this->type} " . implode(' ', $modifiers);
    }

}
