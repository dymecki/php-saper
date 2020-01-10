<?php

declare(strict_types = 1);

namespace App\Domain\Board;

final class Width extends Dimension
{
    public const MIN_WIDTH = 2;
    public const MAX_WIDTH = 80;

    public function __construct(int $value)
    {
        if ($value < self::MIN_WIDTH) {
             throw new \DomainException("Board's width cannot be lower than " . self::MIN_WIDTH);
        }

        if ($value > self::MAX_WIDTH) {
            throw new \DomainException("Board's width cannot be greater than " . self::MAX_WIDTH);
        }

        $this->value = $value;
    }
}