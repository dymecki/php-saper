<?php

declare(strict_types = 1);

namespace App\Domain\Board;

abstract class Dimension
{
    protected int $value;

    public function multiply(self $dimension): int
    {
        return $this->value * $dimension->value();
    }

    public function value(): int
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }
}
