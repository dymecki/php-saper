<?php

declare(strict_types = 1);

namespace App\Views\Html\Tiles;

final class SafeTileHtml extends BaseTileHtml
{
    public function class(): string
    {
        return 'tile safe';
    }

    public function value(): string
    {
        return '';
    }
}