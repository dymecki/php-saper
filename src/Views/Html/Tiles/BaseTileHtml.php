<?php

declare(strict_types = 1);

namespace App\Views\Html\Tiles;

use App\Domain\Tiles\Tile;

abstract class BaseTileHtml
{
    protected Tile $tile;

    public function __construct(Tile $tile)
    {
        $this->tile = $tile;
    }

    abstract public function class(): string;

    abstract public function value(): string;
}