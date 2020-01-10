<?php

declare(strict_types = 1);

namespace App\Domain\Board;

final class Height extends Dimension
{
    public const MIN_HEIGHT = 2;
    public const MAX_HEIGHT = 80;

    public function __construct(int $value)
    {
        if ($value < self::MIN_HEIGHT) {
            throw new \DomainException("Board's height cannot be lower than " . self::MIN_HEIGHT);
        }

        if ($value > self::MAX_HEIGHT) {
            throw new \DomainException("Board's height cannot be greater than " . self::MAX_HEIGHT);
        }

        $this->value = $value;
    }
}