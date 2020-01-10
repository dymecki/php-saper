<?php

declare(strict_types = 1);

namespace App\Views\Html\Dev;

use App\Domain\Tiles\Tile;

final class TileView
{
    private Tile $tile;

    public function __construct(Tile $tile)
    {
        $this->tile = $tile;
    }

    public function render(): string
    {
        $data = $this->data();

        return sprintf('<div class="%s">%s</div>', $data->class, $data->value);
    }

    private function data(): \stdClass
    {
        $data        = new \stdClass();
        $data->class = 'tile';

        if ($this->tile->hasFlag()) {
            $data->class .= ' flag';
            $data->value = '&#9873;';

            return $data;
        }

        if ($this->tile->isClosed()) {
            $data->class .= ' close';
            $data->value = '<a href="' . $this->url() . '"></a>';

            return $data;
        }

        if ($this->tile->isBomb()) {
            $data->class .= ' bomb';
            $data->value = 'x';
        }

        if ($this->tile->isNumber()) {
            $data->class .= ' v' . $this->tile->state()->value();
            $data->value = $this->tile->state()->value();
        }

        if ($this->tile->isSafe()) {
            $data->class .= ' safe';
            $data->value = '';
        }

        return $data;
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