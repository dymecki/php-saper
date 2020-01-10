<?php

declare(strict_types = 1);

namespace App\Views\Html\Tiles;

final class FlagTileHtml extends BaseTileHtml
{
    public function class(): string
    {
        return 'tile flag';
    }

    public function value(): string
    {
        return '&#9873;';
    }
}