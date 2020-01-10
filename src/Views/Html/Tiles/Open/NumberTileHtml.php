<?php

declare(strict_types = 1);

namespace App\Views\Html\Tiles;

final class NumberTileHtml extends BaseTileHtml
{
    public function class(): string
    {
        return 'tile v' . $this->tile->state()->value();
    }

    public function value(): string
    {
        return (string) $this->tile->state()->value();
    }
}