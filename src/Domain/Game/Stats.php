<?php

declare(strict_types = 1);

namespace App\Domain\Game;

final class Stats implements \JsonSerializable
{
    private int                $clicks;
    private \DateTimeImmutable $start;

    public function __construct()
    {
        $this->clicks = 0;
        $this->start  = new \DateTimeImmutable();
    }

    public function click(): void
    {
        $this->clicks++;
    }

    public function clicks(): int
    {
        return $this->clicks;
    }

    public function time(): int
    {
        return time() - $this->start->getTimestamp();
    }

    public function jsonSerialize(): array
    {
        return [
            'clicks' => $this->clicks
        ];
    }
}