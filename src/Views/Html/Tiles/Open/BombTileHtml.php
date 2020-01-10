<?php

declare(strict_types = 1);

namespace App\Views\Html\Tiles\Open;

use App\Views\Html\Tiles\BaseTileHtml;

final class BombTileHtml extends BaseTileHtml
{
    public function class(): string
    {
        return 'tile bomb';
    }

    public function value(): string
    {
        return 'x';
    }
}