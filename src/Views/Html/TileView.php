<?php

declare(strict_types = 1);

namespace App\Views\Html;

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

        return sprintf(
            '<div class="%s" data-x="%s" data-y="%s">%s</div>',
            $data->class,
            $this->tile->position()->x(),
            $this->tile->position()->y(),
            $data->value
        );
    }

    private function data()
    {
//        return TileViewFactory::make($this->tile);

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
//            $data->value = '&#128163;';
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

//        var_dump($this->tile->type());
//        $class = 'App\Views\Html\Tiles\\' . ucfirst($this->tile->type()) . 'TileHtml';
//
//        return new $class($this->tile);
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