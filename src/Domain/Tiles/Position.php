<?php

declare(strict_types = 1);

namespace App\Domain\Tiles;

final class Position implements \JsonSerializable
{
    private int $x;
    private int $y;

    public function __construct(int $x, int $y)
    {
//        if (($x === 0 && $y > 0) || ($x > 0 && $y === 0)) {
        if (($x === 0 || $y === 0) && ($x !== $y)) {
            throw new \DomainException('Position value inconsistency');
        }

        if ($x < 0) {
            throw new \DomainException("Position's x value cannot be negative: $x");
        }

        if ($y < 0) {
            throw new \DomainException("Position's y value cannot be negative: $y");
        }

        $this->x = $x;
        $this->y = $y;
    }

    public function x(): int
    {
        return $this->x;
    }

    public function y(): int
    {
        return $this->y;
    }

    public function equals(self $position): bool
    {
        return $this->x === $position->x && $this->y === $position->y;
    }

    public function label(): string
    {
        return $this->x . '.' . $this->y;
    }

    public function __toString(): string
    {
        return $this->label();
    }

    public function jsonSerialize(): array
    {
        return [
            'x' => $this->x,
            'y' => $this->y
        ];
    }
}