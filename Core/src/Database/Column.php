<?php

namespace ZenithPHP\Core\Database;

class Column
{
    protected string $name;
    protected string $type;
    protected array $modifiers = [];
    protected bool $hasForeignKey = false;

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
        // This specifies the column as a FOREIGN KEY and includes the REFERENCES syntax
        $this->modifiers[] = ", FOREIGN KEY (`{$this->name}`) REFERENCES `$table` (`id`)";
        return $this;
    }

    public function cascadeOnDelete(): self
    {
        if ($this->hasForeignKey) {
            $this->modifiers[] = 'ON DELETE CASCADE';
        }
        return $this;
    }

    public function cascadeOnUpdate(): self
    {
        if ($this->hasForeignKey) {
            $this->modifiers[] = 'ON UPDATE CASCADE';
        }
        return $this;
    }

    public function __toString(): string
    {
        return "`{$this->name}` {$this->type} " . implode(' ', $this->modifiers);
    }
}
