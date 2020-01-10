<?php

declare(strict_types = 1);

namespace App\Views\Html\Tiles;

final class ClosedTileHtml extends BaseTileHtml
{
    public function class(): string
    {
        return 'tile close';
    }

    public function value(): string
    {
        return '<a href="' . $this->url() . '"></a>';
    }

    private function url(): string
    {
        return sprintf(
            '/index.php?x=%s&y=%s',
            $this->tile->position()->x(),
            $this->tile->position()->y()
        );
    }
}